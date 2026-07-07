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
use App\Models\DriverKey;

class AuthController extends Controller
{
    public function Par_register(Request $request)
    {
        $request->validate([
            'fullname' => ['required', 'max:255'],
            'username' => ['required', 'max:255'],
            'email' => ['required', 'max:255', 'email', 'unique:users'],
            'password' => ['required', 'string', 'confirmed', Password::default()],
            'location' => ['required'],
            'city'     => ['required', 'string', 'max:100'],
            'district' => ['required', 'string', 'max:100'],
        ]);

        $user = User::create([
            'name' => $request->input('fullname'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role' => ('P')
        ]);

        $user->parent()->create([
            'location' => $request->input('location'),
            'city'     => $request->input('city'),
            'district' => $request->input('district'),
        ]);

        return redirect()->route('login')->with('success', 'Account created successfully!');
    }

    public function showDriverRegister()
    {
        if (!session('driver_key_id')) {
            return redirect()->route('register')
                ->with('error', 'A valid Driver Key is required to access driver registration.');
        }

        return view('driver-register');
    }

    public function user_login(Request $request){
        $field = $request->validate([
            'email' => ['required', 'max:255', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($field)) {
            $user = Auth::user();
            
            if ($user->role === 'D') {
                $driver = Driver::where('user_id', $user->id)->first();
                if (!$driver || $driver->ver_status !== 'Approved') {
                    Auth::logout();
                    return back()->withErrors(['failed' => 'Your account is pending verification by the manager.']);
                }
            }
            
            Session::put('user_id', $user->id);
            Session::put('user_role', $user->role);
            Session::put('user_name', $user->name);

            if ($user->role === 'A') {
                return redirect()->route('admin.users'); // Admin dashboard
            } elseif ($user->role === 'P') {
                return redirect()->route('main'); // Parent dashboard (welcome screen)
            } elseif ($user->role === 'D') {
                return redirect()->route('main'); // Driver dashboard (welcome screen)
            }

            
        } else {
            return back()->withErrors([
                'failed' => 'Incorrect email or password'
            ]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function Driver_register(Request $request)
    {

        $request->validate([
            'fullname'            => ['required', 'max:255'],
            'username'            => ['required', 'max:255'],
            'email'               => ['required', 'max:255', 'email', 'unique:users'],
            'password'            => ['required', 'min:8', 'confirmed'],
            'vrn'                 => ['required'],
            'bank_name'           => ['required', 'string', 'max:100'],
            'bank_account_number' => ['required', 'string', 'max:50'],
            'spad'                => ['required', 'mimetypes:application/pdf', 'max:1048576'],
            'license'             => ['required', 'mimetypes:application/pdf', 'max:1048576'],
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
    
        // Create the driver
        $driver = $user->driver()->create([
            'VRN'                 => $request->input('vrn'),
            'ver_status'          => 'Pending',
            'bank_name'           => $request->input('bank_name'),
            'bank_account_number' => $request->input('bank_account_number'),
        ]);

        // Save the document
        $doc = $driver->docs()->create([
            'docs' => $pdfData,
            'license' => $license,
        ]);
    
        Verification::create([
            'driver_id' => $driver->id,
            'doc_id'    => $doc->id,
            'ver_status' => 'Pending',
            'rej_reason' => 'N/A',
        ]);

        // Consume the driver key (mark as used, one-time only)
        if (session('driver_key_id')) {
            DriverKey::where('id', session('driver_key_id'))
                ->where('used', false)
                ->update(['used' => true, 'used_at' => now()]);
            session()->forget('driver_key_id');
        }

        return redirect()->route('login')->with('success', 'Account created successfully!');
    }

}