<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DriverLocation;
use Illuminate\Support\Facades\Auth;

class DriverLocationController extends Controller
{
    // Driver posts their GPS coordinates
    public function update(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $driver = Auth::user()->driver;

        DriverLocation::updateOrCreate(
            ['driver_id' => $driver->id],
            [
                'lat'       => $request->lat,
                'lng'       => $request->lng,
                'timestamp' => now(),
            ]
        );

        return response()->json(['status' => 'ok']);
    }

    // Driver clears their location on stop tracking
    public function clear()
    {
        $driver = Auth::user()->driver;
        DriverLocation::where('driver_id', $driver->id)->delete();
        return response()->json(['status' => 'ok']);
    }

    // Parent fetches the latest location of a specific driver
    public function get($driver_id)
    {
        $location = DriverLocation::where('driver_id', $driver_id)->first();

        if (!$location) {
            return response()->json(['status' => 'offline']);
        }

        return response()->json([
            'status'    => 'ok',
            'lat'       => $location->lat,
            'lng'       => $location->lng,
            'timestamp' => $location->timestamp,
        ]);
    }
}
