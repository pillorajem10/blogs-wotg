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

        // Update the user's D-Group leader (the current authenticated user)
        $user->user_dgroup_leader = auth()->user()->id;  // The D-Group leader who approved
        $user->approval_token = null;  // Optionally, clear the token after approval
        $user->save();

        // Redirect back to the dashboard with a success message
        return redirect()->route('dashboard')->with('success', 'User successfully approved as a D-Group member.');
    }
}

