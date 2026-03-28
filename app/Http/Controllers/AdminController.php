<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function viewUsers()
    {
        // Fetch parents (role P) and drivers (role D)
        $parents = User::where('role', 'P')->get();
        $drivers = User::where('role', 'D')->get();
    
        return view('admin', compact('parents', 'drivers'));
    }
    

    public function userDetails($id)
{
    $user = User::find($id);

    return view('user-details', compact('user'));
}

public function showUsers()
{
    $parents = User::where('role', 'P')->get();
    $drivers = User::where('role', 'D')->get();

    return view('admin', compact('parents', 'drivers'));
}


}
