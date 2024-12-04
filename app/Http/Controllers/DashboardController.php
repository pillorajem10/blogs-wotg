<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Ensure the user is authenticated
        if (Auth::check()) {
            // Retrieve the logged-in user's details
            $user = Auth::user();
    
            // Fetch all users where the 'user_dgroup_leader' matches the current user's ID
            $dgroupMembers = User::where('user_dgroup_leader', $user->id)->get();
    
            // Return the view with the user and their D-Group members
            return view('pages.dashboard', compact('user', 'dgroupMembers'));
        }
    
        // If the user is not authenticated, redirect them to the login page
        return redirect()->route('auth.login')->with('error', 'Please log in to access the dashboard.');
    }    
}
