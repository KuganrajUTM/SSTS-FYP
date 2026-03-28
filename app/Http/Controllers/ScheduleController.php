<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Child;
use App\Models\Schedule;
use App\Models\Driver;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use App\Models\User;


class ScheduleController extends Controller
{
    public function Add_Schedule(Request $request)
    {
        
        $userId = Session::get('user_id');
    $driver = Driver::where('user_id', $userId)->first();
    $driverId = $driver->id;

    // Process the Monday - Thursday locations
    if (isset($request->entries['mon_thu'])) {
        foreach ($request->entries['mon_thu'] as $timeSlot => $data) {
            // If location is empty, set it to '-'
            $location = !empty($data['location']) ? $data['location'] : ' ';

            // Save the schedule for Monday - Thursday
            Schedule::create([
                'driver_id' => $driverId,
                'time_slot' => $timeSlot,
                'location' => $location,
                'day' => 1, // Monday-Thursday
            ]);
        }
    }

    // Process the Friday locations
    if (isset($request->entries['fri'])) {
        foreach ($request->entries['fri'] as $timeSlot => $data) {
            // If location is empty, set it to '-'
            $location = !empty($data['location']) ? $data['location'] : ' ';

            // Save the schedule for Friday
            Schedule::create([
                'driver_id' => $driverId,
                'time_slot' => $timeSlot,
                'location' => $location,
                'day' => 2, // Friday
            ]);
        }
    }

    // Redirect back with a success message
    return redirect()->route('schedules.index')->with('success', 'Schedule added successfully!');
    }

    public function View_Schedule()
{
    // Retrieve schedule data from the database
    $schedule = Schedule::all();

    // Pass the schedule data to the view
    return view('schedule.index', ['schedule' => $schedule]);
}

public function index()
{
    $userId = Session::get('user_id');
    $driver = Driver::where('user_id', $userId)->first();

    if (!$userId) {
        return redirect()->route('login')->withErrors(['error' => 'Driver not logged in']);
    }


    if (!$driver) {
        return view('schedule.index')->with('error', 'Driver not found.');
    }

    $schedules = Schedule::where('driver_id', $driver->id)->get();

    // Group data
    $monThuSchedules = $schedules->where('day', 1);  // Monday - Thursday
    $friSchedules = $schedules->where('day', 2);     // Friday

    $monThuTimeSlots = ['6:00am', '6:15am', '6:30am', '6:45am', '7:00am', '7:15am', '1:45pm', '2:00pm', '2:15pm', '2:30pm', '2:45pm'];
    $friTimeSlots = ['6:00am', '6:15am', '6:30am', '6:45am', '7:00am', '7:15am', '12:30pm', '12:45pm', '1:00pm', '1:15pm', '1:30pm'];

    return view('schedule.index', compact('monThuSchedules', 'friSchedules','monThuTimeSlots', 'friTimeSlots'));
}


// In ScheduleController.php
public function edit()
{
    $userId = Session::get('user_id');
    $driver = Driver::where('user_id', $userId)->first();

    $monThuSchedules = Schedule::where('driver_id', $driver->id)
        ->where('day', 1)
        ->orderBy('time_slot')
        ->get();

    $friSchedules = Schedule::where('driver_id', $driver->id)
        ->where('day', 2)
        ->orderBy('time_slot')
        ->get();

    $monThuTimeSlots = ['6:00am', '6:15am', '6:30am', '6:45am', '7:00am', '7:15am', '1:45pm', '2:00pm', '2:15pm', '2:30pm', '2:45pm'];
    $friTimeSlots = ['6:00am', '6:15am', '6:30am', '6:45am', '7:00am', '7:15am', '12:30pm', '12:45pm', '1:00pm', '1:15pm', '1:30pm'];

    return view('schedule.edit', compact('monThuSchedules', 'friSchedules', 'monThuTimeSlots', 'friTimeSlots'));
}



public function update(Request $request)
{
    $userId = Session::get('user_id');
    $driver = Driver::where('user_id', $userId)->first();
    $driverId = $driver->id;

    // Process Monday - Thursday schedules
    if (isset($request->entries['mon_thu'])) {
        foreach ($request->entries['mon_thu'] as $timeSlot => $data) {
            $location = $data['location'] ?? '';

            $schedule = Schedule::where('driver_id', $driverId)
                ->where('day', 1)
                ->where('time_slot', $timeSlot)
                ->first();

            if ($schedule) {
                // Update existing schedule
                $schedule->update(['location' => $location]);
            } else {
                // Create new schedule
                Schedule::create([
                    'driver_id' => $driverId,
                    'time_slot' => $timeSlot,
                    'location' => $location,
                    'day' => 1,
                ]);
            }
        }
    }

    // Process Friday schedules
    if (isset($request->entries['fri'])) {
        foreach ($request->entries['fri'] as $timeSlot => $data) {
            $location = $data['location'] ?? '';

            $schedule = Schedule::where('driver_id', $driverId)
                ->where('day', 2)
                ->where('time_slot', $timeSlot)
                ->first();

            if ($schedule) {
                // Update existing schedule
                $schedule->update(['location' => $location]);
            } else {
                // Create new schedule
                Schedule::create([
                    'driver_id' => $driverId,
                    'time_slot' => $timeSlot,
                    'location' => $location,
                    'day' => 2,
                ]);
            }
        }
    }

    return redirect()->route('schedules.index')->with('success', 'Schedules updated successfully');
}


public function destroy(Request $request)
{
    $userId = Session::get('user_id');
    $driver = Driver::where('user_id', $userId)->first();
    $driverId = $driver->id;

    // Delete all schedules for Friday (day = 2)
    Schedule::where('driver_id', $driverId )->delete();

    // Redirect back with a success message
    return redirect()->route('schedules.index')->with('success', 'All schedules have been deleted.');
}

public function viewSchedule($driver_id, $child_id)
{
    $driver = Driver::findOrFail($driver_id);
    $schedules = Schedule::where('driver_id', $driver_id)->get();

    // Group schedules by day
    $monThuSchedules = $schedules->where('day', 1);  // Monday - Thursday
    $friSchedules = $schedules->where('day', 2);     // Friday

    return view('schedule', compact('driver', 'monThuSchedules', 'friSchedules', 'child_id'));
}



}
