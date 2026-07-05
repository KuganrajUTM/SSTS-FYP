<?php

namespace App\Http\Controllers;

use App\Mail\DriverKeyMail;
use App\Models\DriverKey;
use App\Models\DriverKeyRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class DriverKeyController extends Controller
{
    public function index()
    {
        $keys         = DriverKey::orderBy('created_at', 'desc')->get();
        $totalCount   = $keys->count();
        $availCount   = $keys->where('used', false)->count();
        $usedCount    = $keys->where('used', true)->count();

        $requests        = DriverKeyRequest::orderBy('created_at', 'desc')->get();
        $pendingCount    = $requests->where('status', 'pending')->count();

        return view('admin.driver-keys', compact(
            'keys', 'totalCount', 'availCount', 'usedCount',
            'requests', 'pendingCount'
        ));
    }

    public function store()
    {
        do {
            $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (DriverKey::where('key_code', $code)->exists());

        DriverKey::create(['key_code' => $code, 'used' => false]);

        return back()->with('success', "Driver key {$code} generated successfully.");
    }

    public function destroy(int $id)
    {
        $key = DriverKey::findOrFail($id);

        if ($key->used) {
            return back()->with('error', 'Cannot delete a key that has already been used.');
        }

        $key->delete();

        return back()->with('success', 'Key deleted successfully.');
    }

    public function validateKey(Request $request)
    {
        $request->validate(['key_code' => 'required|string|size:6']);

        $key = DriverKey::where('key_code', $request->key_code)
            ->where('used', false)
            ->first();

        if (!$key) {
            return response()->json([
                'valid'   => false,
                'message' => 'Invalid or already used key. Please contact the manager.',
            ]);
        }

        session(['driver_key_id' => $key->id]);

        return response()->json(['valid' => true]);
    }

    public function showRequest()
    {
        return view('driver-key-request');
    }

    public function storeRequest(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'contact' => 'required|string|max:30',
            'license' => 'required|mimes:pdf|max:5120',
        ]);

        $path = $request->file('license')->store('license-requests', 'public');

        DriverKeyRequest::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'contact'      => $request->contact,
            'license_path' => $path,
            'status'       => 'pending',
        ]);

        return redirect()->route('driver-key.request.show')
            ->with('success', 'Request submitted! The manager will review your license and email your key shortly.');
    }

    public function viewLicense(int $id)
    {
        $keyRequest = DriverKeyRequest::findOrFail($id);

        if (!$keyRequest->license_path || !Storage::disk('public')->exists($keyRequest->license_path)) {
            abort(404, 'License file not found.');
        }

        $filePath = storage_path('app/public/' . $keyRequest->license_path);
        return response()->file($filePath, ['Content-Type' => 'application/pdf']);
    }

    public function sendKey(int $id)
    {
        $keyRequest = DriverKeyRequest::findOrFail($id);

        if ($keyRequest->status === 'fulfilled') {
            return back()->with('error', 'This request has already been fulfilled.');
        }

        // Generate a unique key
        do {
            $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (DriverKey::where('key_code', $code)->exists());

        DriverKey::create(['key_code' => $code, 'used' => false]);

        // Send email
        Mail::to($keyRequest->email)->send(new DriverKeyMail($code, $keyRequest->name));

        // Mark request fulfilled
        $keyRequest->update([
            'status'       => 'fulfilled',
            'fulfilled_at' => now(),
        ]);

        return back()->with('success', "Key {$code} sent to {$keyRequest->email} successfully.");
    }
}
