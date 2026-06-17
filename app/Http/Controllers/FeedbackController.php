<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Parents;
use App\Models\Driver;
use App\Models\Child;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class FeedbackController extends Controller
{
    // Parent views their submitted feedback + form to submit new
    public function parentIndex()
    {
        $userId = Session::get('user_id');
        if (!$userId) return redirect()->route('login');

        $parent = Parents::with('children.driver.user')->where('user_id', $userId)->first();

        $drivers = collect();
        if ($parent) {
            $drivers = $parent->children
                ->filter(fn($c) => $c->driver_id)
                ->map(fn($c) => $c->driver)
                ->unique('id')
                ->values();
        }

        $feedbacks = Feedback::with(['toDriver.user', 'toChild'])
            ->where('from_user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('feedback.parent', compact('drivers', 'feedbacks'));
    }

    // Parent submits rating or complaint
    public function parentStore(Request $request)
    {
        $userId = Session::get('user_id');
        if (!$userId) return redirect()->route('login');

        $request->validate([
            'type'        => 'required|in:rating,complaint',
            'to_driver_id' => 'required|exists:driver,id',
            'rating'      => 'nullable|integer|min:1|max:5',
            'comment'     => 'required|string|max:1000',
        ]);

        Feedback::create([
            'from_user_id' => $userId,
            'to_driver_id' => $request->to_driver_id,
            'type'         => $request->type,
            'rating'       => $request->type === 'rating' ? $request->rating : null,
            'comment'      => $request->comment,
        ]);

        return redirect()->route('feedback.parent')->with('success', 'Feedback submitted successfully.');
    }

    // Driver views their submitted feedback + form to submit complaint about child
    public function driverIndex()
    {
        $userId = Session::get('user_id');
        if (!$userId) return redirect()->route('login');

        $user = User::with('driver')->find($userId);
        if (!$user || !$user->driver) return redirect()->route('main');

        $driver = $user->driver;
        $children = Child::with('parent.user')->where('driver_id', $driver->id)->get();

        $feedbacks = Feedback::with(['toChild.parent.user'])
            ->where('from_user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('feedback.driver', compact('children', 'feedbacks'));
    }

    // Driver submits complaint about a child
    public function driverStore(Request $request)
    {
        $userId = Session::get('user_id');
        if (!$userId) return redirect()->route('login');

        $request->validate([
            'to_child_id' => 'required|exists:child,id',
            'comment'     => 'required|string|max:1000',
        ]);

        Feedback::create([
            'from_user_id' => $userId,
            'to_child_id'  => $request->to_child_id,
            'type'         => 'complaint',
            'comment'      => $request->comment,
        ]);

        return redirect()->route('feedback.driver')->with('success', 'Complaint submitted successfully.');
    }

    // Manager views all feedback
    public function adminIndex()
    {
        $feedbacks = Feedback::with(['fromUser', 'toDriver.user', 'toChild.parent.user'])
            ->orderBy('status')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.feedback', compact('feedbacks'));
    }

    // Manager marks as reviewed + adds remark
    public function review(Request $request, int $id)
    {
        $request->validate([
            'manager_remark' => 'nullable|string|max:1000',
        ]);

        $feedback = Feedback::findOrFail($id);
        $feedback->status = 'reviewed';
        $feedback->manager_remark = $request->manager_remark;
        $feedback->save();

        return redirect()->route('admin.feedback')->with('success', 'Feedback marked as reviewed.');
    }
}
