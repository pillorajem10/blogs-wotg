<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DgroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Ensure the user is authenticated
    }

    public function approve(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'dgroupLeaderId' => 'required|integer|exists:users,id', // Validate dgroupLeaderId
        ]);
    
        // Find the user by email
        $user = User::where('email', $validated['email'])->first();
    
        if (!$user) {
            return redirect()->route('dashboard')->with('error', 'User not found!');
        }
    
        // Check if the provided token matches the one stored in the database
        if ($user->approval_token !== $validated['token']) {
            return redirect()->route('dashboard')->with('error', 'Invalid token!');
        }
    
        // Set the D-Group leader to the one passed in the URL (dgroupLeaderId)
        $user->user_dgroup_leader = $validated['dgroupLeaderId']; // Set the D-Group leader ID from the URL
        $user->approval_token = null;  // Optionally, clear the token after approval
        $user->save();
    
        // Redirect back to the dashboard with a success message
        return redirect()->route('dashboard')->with('success', 'User successfully approved as a D-Group member.');
    }    
}

