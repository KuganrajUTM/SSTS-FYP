<?php

namespace App\Http\Controllers;

use App\Models\Ann;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class AnnController extends Controller
{
    /**
     * Display a listing of the announcements.
     */
    public function index(Request $request)
{

    $userId = Session::get('user_id');
    $userRole = Session::get('user_role');
    $userName = Session::get('user_name');

    // Redirect to login if user is not authenticated
    if (!$userId) {
        return redirect()->route('login')->withErrors(['error' => 'User not logged in']);
    }

    if($userRole === 'P'){
        // Parent's Own Announcements
        $parentAnnouncements = Ann::where('user_id', $userId);

        $driverAnnouncements = Ann::whereHas('user', function ($subQuery) {
                                    $subQuery->where('role', 'D');
                                });
    
    }
    elseif($userRole === 'D')
    {
        // Parent's Own Announcements
        $driverAnnouncements = Ann::where('user_id', $userId);

        // Parent's Own Announcements
        $parentAnnouncements = Ann::whereHas('user', function ($subQuery) {
            $subQuery->where('role', 'P');
        });
    }

    // Apply filters to both queries
    if ($request->filled('date')) {
        $parentAnnouncements->whereDate('created_at', $request->date);
        $driverAnnouncements->whereDate('created_at', $request->date);
    }

    if ($request->filter_option === 'absence') {
        $parentAnnouncements->where('title', 'LIKE', '%absence%');
        $driverAnnouncements->where('title', 'LIKE', '%absence%');
    } elseif ($request->filter_option === 'delay') {
        $parentAnnouncements->where('title', 'LIKE', '%delay%');
        $driverAnnouncements->where('title', 'LIKE', '%delay%');
    }

    return view('ann.index', [
        'parentAnnouncements' => $parentAnnouncements->get(),
        'driverAnnouncements' => $driverAnnouncements->get(),
        'userName' => $userName,
    ]);
}


    /**
     * Show the form for creating a new announcement.
     */
    public function create()
    {
        return view('ann.add');
    }

    /**
     * Store a newly created announcement in the database.
     */
    public function store(Request $request)
    {
        $userId = Session::get('user_id');
        $userRole = Session::get('user_role');
        $userName = Session::get('user_name');

        // Redirect to login if user is not authenticated
        if (!$userId) {
            return redirect()->route('login')->withErrors(['error' => 'User not logged in']);
        }

        // Validate form input
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
        ]);

        if ($userRole === 'P') {
            // Parents can add announcements only for themselves
           Ann::create([
                'title' => $request->title,
                'content' => strip_tags($request->content),
                'user_id' => $userId,
            ]);
        
        } elseif ($userRole === 'D') {
            // Drivers can also create announcements
            Ann::create([
                'title' => $request->title,
                'content' => strip_tags($request->content),
                'user_id' => $userId,
            ]);
        }

        return redirect()->route('ann')->with('success', 'Announcement created successfully.');
    }

    /**
     * Show the form for editing the specified announcement.
     */
    public function edit($id)
{
    $userId = Session::get('user_id');
    $userRole = Session::get('user_role');

    // Redirect to login if user is not authenticated
    if (!$userId) {
        return redirect()->route('login')->withErrors(['error' => 'User not logged in']);
    }

    $announcement = Ann::findOrFail($id);

    // Parents should only edit their own announcements
    if ($userRole === 'P' && $announcement->user_ID !== $userId) {
        return redirect()->route('ann')->withErrors(['error' => 'Unauthorized action']);
    }

    return view('ann.edit', compact('announcement'));
}



    /**
     * Update the specified announcement in the database.
     */
    public function update(Request $request, $id)
{
    $userId = Session::get('user_id');
    $userRole = Session::get('user_role');

    // Redirect to login if user is not authenticated
    if (!$userId) {
        return redirect()->route('login')->withErrors(['error' => 'User not logged in']);
    }

    $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required',
    ]);

    $announcement = Ann::findOrFail($id);

    // Parents should only update their own announcements
    if ($userRole === 'P' && $announcement->user_ID !== $userId) {
        return redirect()->route('ann')->withErrors(['error' => 'Unauthorized action']);
    }

    // Update the announcement
    $announcement->update([
        'title' => $request->title,
        'content' => strip_tags($request->content),
    ]);

    $announcement->save();

    return redirect()->route('ann')->with('success', 'Announcement updated successfully.');
}


    /**
     * Remove the specified announcement from the database.
     */
    // In the destroy method
    public function destroy($id)
    {
        $userId = Session::get('user_id');
        $userRole = Session::get('user_role');
    
        // Redirect to login if user is not authenticated
        if (!$userId) {
            return redirect()->route('login')->withErrors(['error' => 'User not logged in']);
        }
    
        $announcement = Ann::findOrFail($id);
        
        
        // Parents should only delete their own announcements
        if ($userRole === 'P' && $announcement->user_ID !== $userId) {
            return redirect()->route('ann')->withErrors(['error' => 'Unauthorized action']);
        }
    
        try {
            $announcement->delete();
            
        } catch (\Exception $e) {
            return redirect()->route('ann')->withErrors(['error' => 'Deletion failed']);
        }
    
        return redirect()->route('ann')->with('success', 'Announcement deleted successfully.');
    }

}
