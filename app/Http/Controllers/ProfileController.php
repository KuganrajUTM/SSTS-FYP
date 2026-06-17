<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Parents;
use App\Models\Driver;
use App\Models\Child;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function showProfile()
    {
        $user = Auth::user();

        if ($user->role === 'P') {
            $parent = $user->parent;
            $children = $parent->children;

            return view('profile', compact('user', 'parent', 'children'));
        } elseif ($user->role === 'D') {
            $driver = $user->driver;
            $passengersCount = $driver->passengers()->count();

            return view('profile', compact('user', 'driver', 'passengersCount'));
        }
    }

    public function show()
    {
        $user = Auth::user(); // Retrieve the authenticated user

        if ($user->role === 'A') {
            return view('profile', [
                'user' => $user,
                'userName' => $user->name,
                'role' => 'admin'
            ]);
        }

        if ($user->role === 'P') { // Check if the user is a parent
            $parent = $user->parent; // Retrieve parent's data
            $children = Child::where('parent_id', $parent->id)->with('driver')->get();

            return view('profile', [
                'isParent' => true,
                'user' => $user,
                'parent' => $parent,
                'children' => $children,
            ]);
        } elseif ($user->role === 'D') { // Check if the user is a driver
            $driver = $user->driver; // Retrieve driver's data
            $passengersCount = Child::where('driver_id', $driver->id)->count();

            return view('profile', [
                'isParent' => false,
                'user' => $user,
                'driver' => $driver,
                'VRN' => $driver,
                'passengersCount' => $passengersCount,
            ]);
        }
    }
    public function edit()
{
    $user = Auth::user(); // Logged-in user

    if ($user->role === 'A') {
        return view('edit', [
            'user' => $user,
            'role' => 'admin'
        ]);
    }

    if ($user->role === 'P') {
        // Parent-specific data
        $parent = $user->parent;
        $children = $parent->children;
        return view('edit', [
            'user' => $user,
            'parent' => $parent,
            'children' => $children,
        ]);
    } elseif ($user->role === 'D') {
        // Driver-specific data
        $driver = $user->driver;
        return view('edit', [
            'user' => $user,
            'driver' => $driver,
            'VRN' =>$driver,
        ]);
    }

    // Redirect if the role is neither P nor D
    return redirect()->route('profile.show')->with('error', 'Profile type not recognized.');
}


public function update(Request $request)
{
    $user = Auth::user();

    // Common validation
    $request->validate([
        'name' => 'required|string',
        'email' => 'required|email',
    ]);

    // Update user details
    $user->update([
        'name' => $request->input('name'),
        'email' => $request->input('email'),
    ]);

    if ($user->role === 'P') {
        // Parent-specific updates
        $request->validate([
            'location' => 'required|string',
            'city'     => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'children.*.name'        => 'nullable|string',
            'children.*.school_name' => 'nullable|string',
            'children.*.city'        => 'nullable|string|max:100',
            'children.*.district'    => 'nullable|string|max:100',
        ]);

        $user->parent->update([
            'location' => $request->input('location'),
            'phone'    => $request->input('phone'),
            'city'     => $request->input('city'),
            'district' => $request->input('district'),
        ]);

        // Track existing child IDs
        $existingChildIds = [];

        if ($request->has('children')) {
            foreach ($request->input('children') as $childKey => $childData) {
                if (is_numeric($childKey)) {
                    // Update existing child — key is the child's DB id
                    $child = Child::where('id', $childKey)
                        ->where('parent_id', $user->parent->id)
                        ->first();
                    if ($child) {
                        $child->update([
                            'name'        => $childData['name'] ?? $child->name,
                            'school_name' => $childData['school_name'] ?? null,
                            'city'        => $childData['city'] ?? null,
                            'district'    => $childData['district'] ?? null,
                        ]);
                        $existingChildIds[] = $child->id;
                    }
                } else {
                    // Add new child — key starts with 'new_'
                    if (!empty($childData['name'])) {
                        $newChild = Child::create([
                            'name'        => $childData['name'],
                            'school_name' => $childData['school_name'] ?? null,
                            'city'        => $childData['city'] ?? null,
                            'district'    => $childData['district'] ?? null,
                            'parent_id'   => $user->parent->id,
                            'driver_id'   => null,
                        ]);
                        $existingChildIds[] = $newChild->id;
                    }
                }
            }
        }

        // Delete children that were removed from the form
        Child::where('parent_id', $user->parent->id)
            ->whereNotIn('id', $existingChildIds)
            ->delete();
    } elseif ($user->role === 'D') {
        $request->validate([
            'vehicle_info'        => 'required|string',
            'city'                => 'nullable|string|max:100',
            'district'            => 'nullable|string|max:100',
            'bank_name'           => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:50',
        ]);

        $user->driver->update([
            'VRN'                 => $request->input('vehicle_info'),
            'city'                => $request->input('city'),
            'district'            => $request->input('district'),
            'bank_name'           => $request->input('bank_name'),
            'bank_account_number' => $request->input('bank_account_number'),
        ]);
    }

    return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');

}



public function updateLocationInfo(Request $request)
{
    $request->validate([
        'city'     => 'required|string|max:100',
        'district' => 'required|string|max:100',
    ]);

    Auth::user()->driver->update([
        'city'     => $request->city,
        'district' => $request->district,
    ]);

    return redirect()->route('main')->with('success', 'Location info saved.');
}

public function removeAccount()
{
    $user = Auth::user();

    DB::transaction(function () use ($user) {
        // Handle Parent Role Deletion
        if ($user->role === 'P') {
            $parent = $user->parent;
            if ($parent) {
                // Delete all children records associated with this parent
                foreach ($parent->children as $child) {
                    $child->delete();
                }

                // Delete parent record
                $parent->delete();
            }
        }

        // Handle Driver Role Deletion
        if ($user->role === 'D') {
            $driver = $user->driver;
            if ($driver) {
                // Delete the driver record
                $driver->delete();
            }
        }

        // Finally, delete the user record
        $user->delete();
    });

    // Log out the user after account removal
    auth()->logout();

    return redirect()->route('welcome')->with('success', 'Your account has been successfully removed.');
}

}