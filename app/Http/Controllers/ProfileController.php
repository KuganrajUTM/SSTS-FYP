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
            'children.*.name' => 'nullable|string',
            'children.*.school_name' => 'nullable|string',
        ]);

        $user->parent->update(['location' => $request->input('location')]);

        // Track existing child IDs
        $existingChildIds = [];

        if ($request->has('children')) {
            foreach ($request->input('children') as $childData) {
                if (isset($childData['id'])) {
                    // Update existing child
                    $child = Child::find($childData['id']);
                    if ($child) {
                        $child->update($childData);
                        $existingChildIds[] = $child->id;
                    }
                } else {
                    // Add new child
                    $newChild = Child::create([
                        'name' => $childData['name'],
                        'school_name' => $childData['school_name'],
                        'parent_id' => $user->parent->id,
                        'driver_id' => isset($childData['driver_id']) ? $childData['driver_id'] : null, // Assign driver if applicable
                    ]);
                    $existingChildIds[] = $newChild->id;
                }
            }
        }

        // Delete removed children
        Child::where('parent_id', $user->parent->id)
            ->whereNotIn('id', $existingChildIds)
            ->delete();
    } elseif ($user->role === 'D') {
        // Driver-specific updates
        $request->validate([
            'vehicle_info' => 'required|string',
        ]);

        $user->driver->update(['vehicle_info' => $request->input('vehicle_info')]);
    }

    return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');

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