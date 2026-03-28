<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use App\Models\Driver;
use App\Models\Verification;

class AuthController extends Controller
{
    public function Par_register(Request $request)
    {
        $request->validate([
            'fullname' => ['required', 'max:255'],
            'username' => ['required', 'max:255'],
            'email' => ['required', 'max:255', 'email', 'unique:users'],
            'password' => ['required', 'string', 'confirmed', Password::default()],
            'location' => ['required']
        ]);

        $user = User::create([
            'name' => $request->input('fullname'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role' => ('P')
        ]);

        $user->parent()->create([
            'location' => $request->input('location')
        ]);

        return redirect()->route('login')->with('success', 'Account created successfully!');
    }

    public function user_login(Request $request){
        $field = $request->validate([
            'email' => ['required', 'max:255', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($field)) {
            $user = Auth::user();
            // Save only necessary information in session
            $driver_verification = Driver::where('user_id', $user->id)
                                     ->where('ver_status', 'Approved')
                                     ->first();
            
            if(!$driver_verification && $user->role==='D')
            {
                return back()->withErrors(['failed' => 'Your Verification Still Pending']);
            }
            Session::put('user_id', $user->id); // Save user ID
            Session::put('user_role', $user->role); // Save user role
            Session::put('user_name', $user->name); // Save user name

            return redirect()->route('main')->with('success', 'Login successful!');
        }else{
            return back()->withErrors([
                'failed' => 'Incorrect email or password'
            ]);
        }
    }

    public function Driver_register(Request $request)
    {

        $request->validate([
            'fullname' => ['required', 'max:255'],
            'username' => ['required', 'max:255'],
            'email' => ['required', 'max:255', 'email', 'unique:users'],
            'password' => ['required', 'min:8', 'confirmed'],
            'vrn' => ['required'],
            'spad' => ['required', 'mimetypes:application/pdf', 'max:2048'],
            'license' => ['required', 'mimetypes:application/pdf', 'max:2048']
            
        ]);
    
        // Read and encode the file contents
        $pdfData = file_get_contents($request->file('spad')->getRealPath());
        $license = file_get_contents($request->file('license')->getRealPath());
    
        // Create the user
        $user = User::create([
            'name' => $request->input('fullname'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role' => 'D',
        ]);
    
        if (!$user) {
            dd("User creation failed");
        }
    
        // Create the driver
        $driver = $user->driver()->create([
            'VRN' => $request->input('vrn'),
            'ver_status' => 'Pending',
        ]);
    
        if (!$driver) {
            dd("Driver creation failed");
        }
    
        // Save the document
        $doc = $driver->docs()->create([
            'docs' => $pdfData,
            'license' => $license,
        ]);
    
        if (!$doc) {
            dd("Docs creation failed");
        }
        
        Verification::create([
            'admin_id' => 1,
            'driver_id' => $driver->id,
            'doc_id' => $doc->id,
            'ver_status' => 'Pending',
            'rej_reason' => 'N/A',
        ]);

        return redirect()->route('login')->with('success', 'Account created successfully!');
    }
    
}