<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Mail\DgroupMemberApprovalRequest;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showSignupForm()
    {
        if (Auth::check()) {
            return redirect()->route('blogs.index');
        }
        
        return view('pages.signup');
    }





    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_fname' => 'required|string|max:255',
            'user_lname' => 'required|string|max:255',
            'user_nickname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'user_mobile_number' => 'nullable|string|max:15',
            'user_church_name' => 'nullable|string|max:255',
            'user_birthday' => 'nullable|date',
            'user_country' => 'nullable|string|max:100',
            'user_city' => 'nullable|string|max:100',
            'user_dgroup_leader' => 'nullable|string|max:255',
            'user_ministry' => 'nullable|string|max:255',
            'user_already_a_dgroup_leader' => 'required|in:0,1',
            'user_already_a_dgroup_member' => 'required|in:0,1',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
    
        // Initialize the dgroup leader ID
        $user_dgroup_leader_id = null;
        $approvalToken = null;
    
        if ($request->has('user_dgroup_leader') && !empty($request->user_dgroup_leader)) {
            // Find the D-Group leader by email
            $dgroupLeader = User::where('email', $request->user_dgroup_leader)->first();
    
            if (!$dgroupLeader) {
                return redirect()->back()->with('error', 'This D-Group leader does not exist, kindly try again.')->withInput();
            }
    
            // Set D-Group leader ID
            $user_dgroup_leader_id = $dgroupLeader->id;
    
            // Generate an approval token for the user
            $approvalToken = Str::random(60);
    
            // Save the approval token to the user record
            $user = User::create([
                'user_fname' => $request->user_fname,
                'user_lname' => $request->user_lname,
                'user_nickname' => $request->user_nickname,
                'email' => $request->email,
                'user_gender' => $request->user_gender,
                'password' => Hash::make($request->password),
                'user_role' => 'member',
                'user_mobile_number' => $request->user_mobile_number,
                'user_church_name' => $request->user_church_name,
                'user_birthday' => $request->user_birthday,
                'user_country' => $request->user_country,
                'user_city' => $request->user_city,
                'user_dgroup_leader' => null, // initially null
                'user_ministry' => $request->user_ministry,
                'user_already_a_dgroup_leader' => $request->user_already_a_dgroup_leader == '1',
                'user_already_a_dgroup_member' => $request->user_already_a_dgroup_member == '1',
                'approval_token' => $approvalToken,  // Save the token here
            ]);
    
            // Send the approval email to the D-Group leader with the approval token
            \Mail::to($dgroupLeader->email)->send(new \App\Mail\DgroupMemberApprovalRequest($dgroupLeader, $request->email, $approvalToken, $dgroupLeader->id));    
        } else {
            // If no D-Group leader is provided, create the user without an approval token
            $user = User::create([
                'user_fname' => $request->user_fname,
                'user_lname' => $request->user_lname,
                'user_nickname' => $request->user_nickname,
                'email' => $request->email,
                'user_gender' => $request->user_gender,
                'password' => Hash::make($request->password),
                'user_role' => 'member',
                'user_mobile_number' => $request->user_mobile_number,
                'user_church_name' => $request->user_church_name,
                'user_birthday' => $request->user_birthday,
                'user_country' => $request->user_country,
                'user_city' => $request->user_city,
                'user_dgroup_leader' => null,
                'user_ministry' => $request->user_ministry,
                'user_already_a_dgroup_leader' => $request->user_already_a_dgroup_leader == '1',
                'user_already_a_dgroup_member' => $request->user_already_a_dgroup_member == '1',
            ]);
        }
    
        // Log the user in
        Auth::login($user);
    
        // Redirect to the dashboard
        return redirect()->route('dashboard')->with('success', 'Registration successful! You are now logged in.');
    }
      
    
    


    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('blogs.index');
        }

        return view('pages.login');
    }





    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
    
        $credentials = $request->only('email', 'password');

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            return redirect()->route('dashboard')->with('success', 'Registration successful! You are now logged in.');
        }
    
        return redirect()->route('auth.login')->with('error', 'The credentials you provided do not match our records.');
    }





    public function logout()
    {
        Auth::logout();
        return redirect()->route('auth.login')->with('success', 'Logged out successfully!');
    }
}
