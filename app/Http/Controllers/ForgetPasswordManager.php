<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Models\User; 

class ForgetPasswordManager extends Controller
{
    public function view_password(){
        return view('forget-password');
    }

    public function reset_password(Request $request){
        $request->validate([
            'email' => "required|email|exists:users",
        ]);

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => Carbon::now()]
        );

        $user = User::where('email', $request->email)->first();
        $username = $user->name;

        Mail::send("emails.forget-password", ['token' => $token, 'name' => $username], function ($message) use ($request){
            $message->to($request->email);
            $message->subject("Reset Your Password - SSTS");
        });

        return redirect()->to(route('forgot-password'))->with("success", "We have sent an email to reset password");

    }

    public function resetPassword($token){
        $record = DB::table('password_reset_tokens')->where('token', $token)->first();

        if (!$record) {
            return redirect()->route('forgot-password')->with('error', 'This reset link is invalid or has expired. Please request a new one.');
        }

        return view('new-password', ['token' => $token, 'email' => $record->email]);
    }

    public function updatePassword(Request $request){
        $request->validate([
            'password' => "required|string|min:6|confirmed",
            'password_confirmation' => "required"
        ]);

        $record = DB::table('password_reset_tokens')->where('token', $request->token)->first();

        if (!$record) {
            return redirect()->route('forgot-password')->with('error', 'This reset link is invalid or has expired. Please request a new one.');
        }

        User::where('email', $record->email)->update([
            'password' => Hash::make($request->password)
        ]);

        DB::table('password_reset_tokens')->where('token', $request->token)->delete();

        return redirect()->to(route('login'))->with('success', 'Password reset was successful.');
    }
}
