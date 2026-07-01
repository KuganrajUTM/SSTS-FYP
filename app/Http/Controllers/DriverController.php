<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\Child;
use App\Models\Document;


class DriverController extends Controller
{
    public function view_pdf(Request $request)
    {
        $driverId = $request->query('id');
        $docsName = $request->query('docs_name');

        $document = Document::where('driver_id', $driverId)->first();

        if (!$document) {
            abort(404, 'Document not found.');
        }

        if ($docsName === 'LIC') {
            $pdf = $document->license;
            $filename = 'license.pdf';
        } else {
            $pdf = $document->docs;
            $filename = 'spad.pdf';
        }

        if (!$pdf) {
            abort(404, 'File not uploaded.');
        }

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }

    public function list($child_id)
    {
        // Retrieve all drivers from the database
        $drivers = Driver::all();
    
        // Find the child by ID
        $child = Child::findOrFail($child_id);
    
        // Pass drivers and child data to the view
        return view('list', compact('drivers', 'child'));
    }
    

    public function view(Driver $drivers)
    {
        // Pass the specific driver to the view
        return view('list', compact('drivers')); // Replace 'list' with the correct view name.
    }

// public function showList(Request $request)
// {
//     $childId = $request->query('child_id'); // Get the child ID from the query string
//     $drivers = Driver::all(); // Fetch all drivers from the database
    
//     return view('list', compact('drivers', 'childId'));
// }

// public function showDrivers()
// {
//     // Fetch all drivers from the database
//     $drivers = Driver::all();

//     // Pass the drivers to the view
//     return view('list', compact('drivers'));
// }

public function index($child_id)
{
    // Retrieve the child and parent's location
    $child = Child::with('parent')->findOrFail($child_id);
    $parent_location = $child->parent->location;

    // Retrieve drivers and their schedule locations
    $drivers = Driver::with('user', 'schedules')->get()->map(function ($driver) {
        $driver->schedule_locations = $driver->schedules->pluck('location')->toArray(); // Get all locations from the schedule
        return $driver;
    });

    return view('list', compact('drivers', 'child', 'parent_location'));
}


public function assign(Request $request)
{
    $request->validate([
        'child_id' => 'required|exists:children,id',
        'driver_id' => 'required|exists:drivers,id',
    ]);

    // Find the child and update the driver ID
    $child = Child::findOrFail($request->child_id);
    $child->driver_id = $request->driver_id;
    $child->save();

    return redirect()->route('profile.edit')->with('success', 'Driver assigned successfully.');
}

public function detail($id, $Cid,  Request $request)
{
    // Fetch the driver by ID with its related user
    $driver = Driver::with('user')->find($id);

    // Handle the case where the driver is not found
    if (!$driver) {
        return redirect()->route('list')->with('error', 'Driver not found.');
    }

    $child = Child::find($Cid);

    // Pass the driver and child to the detail view
    return view('detail', compact('driver', 'child'));
}



// public function assignDriver($child_id, $driver_id)
// {
//     // Logic for assigning the driver to the child
//     $child = Child::findOrFail($child_id);
//     $driver = Driver::findOrFail($driver_id);

//     // Update child's assignment or create a new relationship
//     $child->driver_id = $driver->id; // Assuming you have a driver_id field in the child table
//     $child->save();

//     return redirect()->route('driver.schedule', ['driver_id' => $driver_id, 'child_id' => $child_id])->with('success', 'Driver assigned successfully!');
// }



public function addDriverToChild(Request $request, $child_id, $driver_id)
{
    // Fetch the child and driver from the database
    $child = Child::find($child_id);
    $driver = Driver::find($driver_id);

    // Check if the child and driver exist
    if (!$child || !$driver) {
        return redirect()->back()->with('error', 'Invalid child or driver.');
    }

    // Associate the driver with the child (modify this based on your relationship)
    $child->driver_id = $driver->id; // Example: update a column in the child's table
    $child->save();

    // Redirect back with a success message
    return redirect()->route('profile.show')->with('success', 'Driver successfully assigned to child.');
}

public function driverList(Request $request)
{
    // Get the logged-in parent's location
    $parent = auth()->user()->parent;
    $parentLocation = $parent->location;

    // Get the child's school name
    $child = Child::find($request->child_id);

    // Fetch all drivers
    $drivers = Driver::with('user', 'schedules');

    if ($request->has('filter_location')) {
        $drivers = $drivers->whereHas('schedules', function ($query) use ($parentLocation, $child) {
            $query->where('location', $parentLocation)
                  ->orWhere('location', $child->school_name);
        });
    }

    $drivers = $drivers->get();

    return view('list', [
        'drivers' => $drivers,
        'parent_location' => $parentLocation,
        'child' => $child,
    ]);
}

}


