<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MemberRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DgroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Ensure the user is authenticated
    }

    public function approve(Request $request)
    {
        // Log the incoming request data (this will log all input parameters)
        Log::info('Approve request received:', [
            'user_id' => Auth::id(),
            'request_data' => $request->all(),
        ]);

        // Validate the incoming request
        $validated = $request->validate([
            'email' => 'required|email',
            'dgroupLeaderId' => 'required|integer|exists:users,id', // Validate dgroupLeaderId
        ]);

        // Log the validated data
        Log::info('Validated request data:', $validated);

        // Ensure the current authenticated user is the D-Group leader
        $user = Auth::user();

        // Find the user by email
        $member = User::where('email', $validated['email'])->first();

        if (!$member) {
            Log::warning('User not found for approval:', ['email' => $validated['email']]);
            return redirect()->route('dashboard')->with('error', 'User not found!');
        }

        // Set the D-Group leader to the one passed in the URL (dgroupLeaderId)
        $member->user_dgroup_leader = $validated['dgroupLeaderId']; // Assign the D-Group leader ID from the validated request
        $member->approval_token = null;  // Optionally, clear the token after approval
        $member->save();

        // Log the approval action
        Log::info('User approved as D-Group member:', [
            'user_id' => $member->id,
            'dgroup_leader_id' => $validated['dgroupLeaderId'],
        ]);

        // Optionally, update the status of the member request to 'approved'
        $memberRequest = MemberRequest::where('user_id', $member->id)
                                      ->where('dgroup_leader_id', $validated['dgroupLeaderId'])
                                      ->first();

        if ($memberRequest) {
            $memberRequest->status = 'approved';
            $memberRequest->save();

            // Log the update to the member request status
            Log::info('Member request status updated to approved:', [
                'member_request_id' => $memberRequest->id,
                'status' => 'approved',
            ]);
        }

        // Redirect back to the dashboard with a success message
        Log::info('Redirecting to dashboard after successful approval.');
        return redirect()->route('dashboard')->with('success', 'User successfully approved as a D-Group member.');
    }
}
