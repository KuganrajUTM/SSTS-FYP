<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Driver;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::with('driver.user')->get();
        $drivers = Driver::with('user')
            ->whereHas('verification', fn($q) => $q->where('ver_status', 'Approved'))
            ->get();

        return view('vehicle.index', compact('vehicles', 'drivers'));
    }

    public function create()
    {
        $drivers = Driver::with('user')
            ->whereHas('verification', fn($q) => $q->where('ver_status', 'Approved'))
            ->get();

        return view('vehicle.add', compact('drivers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vehicle_number'   => 'required|string|unique:vehicles,vehicle_number',
            'model_name'       => 'required|string',
            'legal_document'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'apad_expiry_date' => 'required|date',
            'driver_id'        => 'nullable|exists:driver,id',
        ]);

        $docPath = null;
        if ($request->hasFile('legal_document')) {
            $docPath = $request->file('legal_document')->store('vehicle_docs', 'public');
        }

        Vehicle::create([
            'vehicle_number'   => $request->vehicle_number,
            'model_name'       => $request->model_name,
            'legal_document'   => $docPath,
            'apad_expiry_date' => $request->apad_expiry_date,
            'driver_id'        => $request->driver_id ?: null,
        ]);

        return redirect()->route('vehicles.index')->with('success', 'Vehicle added successfully.');
    }

    public function edit($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $drivers = Driver::with('user')
            ->whereHas('verification', fn($q) => $q->where('ver_status', 'Approved'))
            ->get();

        return view('vehicle.edit', compact('vehicle', 'drivers'));
    }

    public function update(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);

        $request->validate([
            'vehicle_number'   => 'required|string|unique:vehicles,vehicle_number,' . $id,
            'model_name'       => 'required|string',
            'legal_document'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'apad_expiry_date' => 'required|date',
            'driver_id'        => 'nullable|exists:driver,id',
        ]);

        $docPath = $vehicle->legal_document;
        if ($request->hasFile('legal_document')) {
            $docPath = $request->file('legal_document')->store('vehicle_docs', 'public');
        }

        $vehicle->update([
            'vehicle_number'   => $request->vehicle_number,
            'model_name'       => $request->model_name,
            'legal_document'   => $docPath,
            'apad_expiry_date' => $request->apad_expiry_date,
            'driver_id'        => $request->driver_id ?: null,
        ]);

        return redirect()->route('vehicles.index')->with('success', 'Vehicle updated successfully.');
    }

    public function destroy($id)
    {
        Vehicle::findOrFail($id)->delete();

        return redirect()->route('vehicles.index')->with('success', 'Vehicle deleted successfully.');
    }
}
