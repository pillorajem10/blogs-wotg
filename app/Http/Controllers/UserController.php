<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MemberRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Ensure the user is authenticated
    }
    
    public function index()
    {
        $users = User::all();
        return view('pages.users', compact('users'));
    }

    public function edit()
    {
        $user = auth()->user();  // Get the current authenticated user
    
        // Check if the user already has a D-Group Leader ID set
        $dgroup_leader_email = null;
        if ($user->user_dgroup_leader) {
            $dgroup_leader = User::find($user->user_dgroup_leader); // Assuming user_dgroup_leader stores the leader's user ID
            if ($dgroup_leader) {
                $dgroup_leader_email = $dgroup_leader->email;  // Get the leader's email
            }
        }
    
        return view('pages.editProfile', compact('user', 'dgroup_leader_email'));
    }
    

    public function update(Request $request)
    {
        $user = auth()->user();  // Get the current authenticated user
    
        // Validate the incoming data
        $validated = $request->validate([
            'user_fname' => 'required|string|max:255',
            'user_lname' => 'required|string|max:255',
            'user_nickname' => 'required|string|max:255',
            'user_gender' => 'required|string',
            'user_mobile_number' => 'nullable|string|max:15',
            'user_birthday' => 'required|date',
            'user_ministry' => 'required|string',
            'user_already_a_dgroup_leader' => 'required|boolean',
            'user_already_a_dgroup_member' => 'required|boolean',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'user_church_name' => 'required|string|max:255',
            'user_country' => 'required|string|max:255',
            'user_city' => 'required|string|max:255',
            'user_meeting_day' => 'required|string|in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'user_meeting_time' => 'required|date_format:H:i',
        ]);        
    
        // Check if the D-Group Leader email has changed
        $dgroup_leader_email_changed = false;
        $new_dgroup_leader_email = $request->user_dgroup_leader;
        $new_dgroup_leader = User::where('email', $new_dgroup_leader_email)->first();
    
        if ($new_dgroup_leader) {
            $new_dgroup_leader_id = $new_dgroup_leader->id;
        } else {
            $new_dgroup_leader_id = null;
        }
    
        if ($user->user_dgroup_leader != $new_dgroup_leader_id) {
            $dgroup_leader_email_changed = true;
        }
    
        // Update the user's basic data (excluding D-Group Leader)
        $user->update($validated);
    
        // If D-Group Leader email is changed, send approval request to new D-Group leader
        if ($dgroup_leader_email_changed && !empty($new_dgroup_leader_email)) {
            // Find the new D-Group leader by email
            $dgroupLeader = User::where('email', $new_dgroup_leader_email)->first();
    
            if (!$dgroupLeader) {
                return redirect()->route('profile.edit')->with('error', 'This email is not yet registered. Please ask your D-Group leader to register first so they can accept your request.')->withInput();
            }
    
            // Generate a new approval token (for the approval request)
            $approvalToken = Str::random(60);
    
            // Save the approval token to the user's record
            $user->approval_token = $approvalToken;
            $user->save();
    
            // Send the approval email to the new D-Group leader
            \Mail::to($dgroupLeader->email)->send(new \App\Mail\DgroupMemberApprovalRequest($dgroupLeader, $request->email, $approvalToken, $dgroupLeader->id));
    
            // Save the MemberRequest record for the D-Group leader change request
            MemberRequest::create([
                'user_id' => $user->id,
                'dgroup_leader_id' => $dgroupLeader->id,
                'status' => 'pending', // Initially set the status to 'pending'
            ]);
    
            // Return with a success message (indicating that the approval request is sent)
            return redirect()->route('profile.edit')->with('success', 'Your request to change the D-Group leader has been sent for approval!');
        }
    
        // Redirect with success message for other profile updates
        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
    }
    




    public function updateProfilePicture(Request $request)
    {
        // Log the incoming request for debugging
        Log::info('Profile picture upload request:', [
            'user_id' => auth()->id(),
            'input_data' => $request->all(),
        ]);
    
        // Get the currently authenticated user
        $user = auth()->user();
    
        // Validate the uploaded file
        $request->validate([
            'user_profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif,jfif|max:5048' // Only allow images (max 5MB)
        ]);
    
        // Get the uploaded file
        $file = $request->file('user_profile_picture');
    
        // Log file info for debugging
        Log::info('Uploaded file details:', [
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'file_mime_type' => $file->getMimeType()
        ]);
    
        // Convert the file to binary
        $binaryData = file_get_contents($file);
    
        // Save the binary data in the database
        $user->user_profile_picture = $binaryData;
        $user->save();
    
        // Log the database update success
        Log::info('Profile picture saved for user:', [
            'user_id' => $user->id,
            'profile_picture_size' => strlen($binaryData)
        ]);
    
        // Redirect to the profile page with success message
        return redirect()->route('dashboard')->with('success', 'Profile picture updated successfully!');
    }    
}
