<?php

namespace App\Http\Controllers;
use App\Models\Verification;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        try {
            $verification = Verification::where('driver_id', $driverId)->first();

            if (!$verification) {
                return redirect()->route('driver_verification')
                    ->with('error', 'Verification record not found for driver #' . $driverId);
            }

            $verification->ver_status = $request->status;
            if ($request->status === 'Rejected') {
                $verification->rej_reason = $request->rejection_reason ?? 'No reason provided';
            } else {
                $verification->rej_reason = 'N/A';
            }

            $result = $verification->save();

            if (!$result) {
                return redirect()->route('driver_verification')
                    ->with('error', 'Save returned false — no changes were written.');
            }

            // Sync status to driver table so login check works
            Driver::where('id', $driverId)->update(['ver_status' => $request->status]);

            return redirect()->route('driver_verification')
                ->with('success', 'Status updated to ' . $request->status . ' successfully.');
        } catch (\Exception $e) {
            return redirect()->route('driver_verification')
                ->with('error', 'DB error: ' . $e->getMessage());
        }
    }

    public function updateLicenseExpiry(Request $request, $driverId)
    {
        $request->validate([
            'license_expiry_date' => 'required|date',
        ]);

        try {
            $verification = Verification::where('driver_id', $driverId)->first();

            if (!$verification) {
                return redirect()->route('driver_verification')
                    ->with('error', 'Verification record not found for driver #' . $driverId);
            }

            $verification->license_expiry_date = $request->license_expiry_date;
            $result = $verification->save();

            if (!$result) {
                return redirect()->route('driver_verification')
                    ->with('error', 'Save returned false — no changes were written.');
            }

            return redirect()->route('driver_verification')
                ->with('success', 'License expiry date updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('driver_verification')
                ->with('error', 'DB error: ' . $e->getMessage());
        }
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