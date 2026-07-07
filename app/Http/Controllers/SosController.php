<?php

namespace App\Http\Controllers;

use App\Models\SosMessage;
use App\Models\User;
use App\Models\Parents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SosController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'audio' => 'required|file|max:20480',
        ]);

        $userId = Session::get('user_id') ?? Auth::id();

        if (!$userId) {
            return response()->json(['status' => 'error', 'message' => 'Session expired. Please log in again.'], 401);
        }

        $user = User::with('driver')->find($userId);

        if (!$user || !$user->driver) {
            return response()->json(['status' => 'error', 'message' => 'No driver record linked to this account.'], 403);
        }

        $driver    = $user->driver;
        $file      = $request->file('audio');
        $ext       = $file->getClientOriginalExtension() ?: 'webm';
        $filename  = time() . '_' . $driver->id . '_sos.' . $ext;

        $sosDir = public_path('sos-audio');
        if (!file_exists($sosDir)) {
            mkdir($sosDir, 0755, true);
        }
        $file->move($sosDir, $filename);

        SosMessage::create([
            'driver_id'  => $driver->id,
            'audio_path' => $filename,
            'transcript' => $request->input('transcript') ?: null,
        ]);

        return response()->json(['status' => 'ok', 'message' => 'SOS message sent.']);
    }

    public function parentIndex()
    {
        $userId = Session::get('user_id') ?? Auth::id();

        if (!$userId) {
            return redirect()->route('login');
        }

        $parent = Parents::with('children')->where('user_id', $userId)->first();

        $driverIds = $parent ? $parent->children->pluck('driver_id')->filter()->unique()->values() : collect();

        $messages = SosMessage::with('driver.user')
            ->whereIn('driver_id', $driverIds)
            ->where('deleted_by_parent', false)
            ->where('created_at', '>=', now()->subHours(24))
            ->orderBy('created_at', 'desc')
            ->get();

        return view('parent.sos', compact('messages'));
    }

    public function parentDestroy(int $id)
    {
        $userId = Session::get('user_id') ?? Auth::id();

        if (!$userId) {
            return redirect()->route('login');
        }

        $sos = SosMessage::findOrFail($id);
        $sos->deleted_by_parent = true;
        $sos->save();

        if ($sos->deleted_by_admin) {
            $filePath = public_path('sos-audio/' . $sos->audio_path);
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
            $sos->delete();
        }

        return redirect()->route('parent.sos')->with('success', 'SOS message deleted.');
    }

    public function destroy(int $id)
    {
        $userId   = Session::get('user_id') ?? Auth::id();
        $userRole = Session::get('user_role') ?? Auth::user()?->role;

        if (!$userId || $userRole !== 'P') {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $sos = SosMessage::findOrFail($id);

        $filePath = public_path('sos-audio/' . $sos->audio_path);
        if (file_exists($filePath)) {
            @unlink($filePath);
        }

        $sos->delete();

        return response()->json(['status' => 'ok']);
    }
}
