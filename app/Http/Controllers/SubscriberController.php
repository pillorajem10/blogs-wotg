<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    // Show the sign-up form for subscribers
    public function showSignupForm()
    {
        return view('pages.signupSubscriber');
    }  

    public function signup(Request $request)
    {
        // Validate the request
        $request->validate([
            'sub_fname' => 'required|string|max:255',
            'sub_lname' => 'required|string|max:255',
            'sub_gender' => 'required|string',
            'sub_age' => 'required|integer',
            'sub_email' => 'required|string|email|max:255|unique:subscribers',
            'sub_country' => 'required|string|max:255',
            'sub_city' => 'required|string|max:255',
        ]);
    
        // Create a new subscriber with the validated data
        Subscriber::create($request->only([
            'sub_fname',
            'sub_lname',
            'sub_gender',
            'sub_age',
            'sub_email',
            'sub_country',
            'sub_city',
        ]));
    
        // Redirect or return response
        return redirect()->route('subscribers.signup')->with('success', 'Thank you for subscribing you will receive notification everyday.');
    }
}
