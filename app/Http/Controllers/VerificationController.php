<?php

namespace App\Http\Controllers;
use App\Models\Verification;
use App\Models\Driver;
use App\Models\User;

use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function updateStatus(Request $request, $driverId)
    {
        // Validate the status
        $request->validate([
        'status' => 'required|string|in:pending,approved,rejected',
        ]);

        // Find the verification record by driver_id
        $verification = Verification::where('driver_id', $driverId)->first();

        if (!$verification) {
            return response()->json(['success' => false, 'message' => 'Driver verification not found.'], 404);
        }

        // Update the status
        $verification->ver_status = $request->status;
        if ($request->status !== 'rejected') {
            $verification->rej_reason = 'N/A'; // Clear rejection reason
        }
        $verification->save();

        // Return a success response
        return response()->json(['success' => true]);
    }
    public function delete($driverId)
{
    // Find the verification record by driver_id
    $verification = Verification::where('driver_id', $driverId)->first();

    if (!$verification) {
        return response()->json(['success' => false, 'message' => 'Driver verification not found.'], 404);
    }

    // Delete the verification record
    $driver = Driver::find($driverId);
    if (!$driver) {
        return response()->json(['success' => false, 'message' => 'Driver not found.'], 404);
    }
    $user = User::where('id', $driver->user_id)->first();
    $verification->delete();
    $driver->delete();
    $user->delete();

    // Optionally, delete the driver record

    return response()->json(['success' => true, 'message' => 'Driver registration deleted successfully.']);
}


    public function index()
    {
        // Fetch all driver records (or use appropriate filters)
        $drivers = Driver::with('user')->get();
        
        return view('verification.driver', compact('drivers'));
    }

    public function saveRejectionReason(Request $request, $driverId)
    {
        // Validate the rejection reason
    $request->validate([
        'rejection_reason' => 'required|string|max:255',
    ]);

    // Find the verification record by driver_id (assuming driver_id is the foreign key)
    $verification = Verification::where('driver_id', $driverId)->first();

    // If no record is found, return an error response
    if (!$verification) {
        return response()->json(['success' => false, 'message' => 'Driver verification not found.'], 404);
    }

    // Update the rejection reason and status
    $verification->rej_reason = $request->rejection_reason;
    $verification->ver_status = 'rejected'; // Update the status to rejected
    $verification->save();


    // Return success response
    return response()->json(['success' => true]);
    }
    
}
