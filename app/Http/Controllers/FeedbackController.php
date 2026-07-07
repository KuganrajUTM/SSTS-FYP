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

        $parentRecord = $parent;
        $receivedFeedbacks = $parent
            ? Feedback::with(['fromUser'])
                ->where('to_parent_id', $parent->id)
                ->orderBy('created_at', 'desc')
                ->get()
            : collect();

        return view('feedback.parent', compact('drivers', 'feedbacks', 'receivedFeedbacks'));
    }

    // Parent submits rating or complaint
    public function parentStore(Request $request)
    {
        $userId = Session::get('user_id');
        if (!$userId) return redirect()->route('login');

        $target = $request->input('target', 'driver');

        $rules = [
            'type'    => 'required|in:rating,complaint',
            'comment' => 'required|string|max:1000',
            'rating'  => 'nullable|integer|min:1|max:5',
        ];

        if ($target === 'driver') {
            $rules['to_driver_id'] = 'required|exists:driver,id';
        }

        $request->validate($rules);

        Feedback::create([
            'from_user_id' => $userId,
            'to_driver_id' => $target === 'driver' ? $request->to_driver_id : null,
            'type'         => $request->type,
            'rating'       => $request->type === 'rating' ? $request->rating : null,
            'comment'      => $request->comment,
        ]);

        return redirect()->route('feedback.parent')->with('success', 'Feedback submitted successfully.');
    }

    // Driver views their submitted feedback + form to submit feedback about management or parent
    public function driverIndex()
    {
        $userId = Session::get('user_id');
        if (!$userId) return redirect()->route('login');

        $user = User::with('driver.children.parent.user')->find($userId);
        if (!$user || !$user->driver) return redirect()->route('main');

        $parents = $user->driver->children
            ->filter(fn($c) => $c->parent_id)
            ->map(fn($c) => $c->parent)
            ->filter()
            ->unique('id')
            ->values();

        $feedbacks = Feedback::with(['toParent.user'])
            ->where('from_user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('feedback.driver', compact('feedbacks', 'parents'));
    }

    // Driver submits feedback or complaint to management or a parent
    public function driverStore(Request $request)
    {
        $userId = Session::get('user_id');
        if (!$userId) return redirect()->route('login');

        $target = $request->input('target', 'management');

        $rules = [
            'type'    => 'required|in:feedback,complaint',
            'comment' => 'required|string|max:1000',
        ];
        if ($target === 'parent') {
            $rules['to_parent_id'] = 'required|exists:parent,id';
        }

        $request->validate($rules);

        Feedback::create([
            'from_user_id' => $userId,
            'to_parent_id' => $target === 'parent' ? $request->to_parent_id : null,
            'type'         => $request->type,
            'comment'      => $request->comment,
        ]);

        return redirect()->route('feedback.driver')->with('success', 'Submitted successfully.');
    }

    // Manager views all feedback
    public function adminIndex()
    {
        $feedbacks = Feedback::with(['fromUser', 'toDriver.user', 'toChild.parent.user', 'toParent.user'])
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
