<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Ensure the user is authenticated
        if (Auth::check()) {
            // Retrieve the logged-in user's details
            $user = Auth::user();

            // Return the view from the correct location
            return view('pages.dashboard', compact('user'));
        }

        // If the user is not authenticated, redirect them to the login page
        return redirect()->route('auth.login')->with('error', 'Please log in to access the dashboard.');
    }
}
