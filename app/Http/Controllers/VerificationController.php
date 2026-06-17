<?php

namespace App\Http\Controllers;
use App\Models\Verification;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function index()
    {
        $drivers = Driver::with(['user', 'verification'])->get();
        return view('verification.driver', compact('drivers'));
    }

    public function updateStatus(Request $request, $driverId)
    {
        $request->validate([
            'status' => 'required|in:Pending,Approved,Rejected',
            'rejection_reason' => 'nullable|string|max:255',
        ]);

        $verification = Verification::where('driver_id', $driverId)->first();

        if (!$verification) {
            return back()->with('error', 'Verification record not found.');
        }

        $verification->ver_status = $request->status;
        if ($request->status === 'Rejected') {
            $verification->rej_reason = $request->rejection_reason ?? 'No reason provided';
        } else {
            $verification->rej_reason = null;
        }

        $verification->save();

        return back()->with('success', 'Status updated successfully!');
    }

    public function updateLicenseExpiry(Request $request, $driverId)
    {
        $request->validate([
            'license_expiry_date' => 'required|date',
        ]);

        $verification = Verification::where('driver_id', $driverId)->first();

        if (!$verification) {
            return back()->with('error', 'Verification record not found.');
        }

        $verification->license_expiry_date = $request->license_expiry_date;
        $verification->save();

        return back()->with('success', 'License expiry date updated successfully.');
    }

    public function delete($driverId)
    {
        $verification = Verification::where('driver_id', $driverId)->first();
        
        if (!$verification) {
            return back()->with('error', 'Verification not found.');
        }

        $driver = Driver::find($driverId);
        if ($driver) {
            $user = User::find($driver->user_id);
            $driver->delete();
            if ($user) $user->delete();
        }
        
        $verification->delete();

        return back()->with('success', 'Driver registration deleted successfully.');
    }
}