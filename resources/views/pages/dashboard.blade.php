@extends('layouts.layout')

@section('title', 'Dashboard')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css?v=3.3') }}">
@endsection

@section('content')

    <div id="loading-overlay" class="loading-overlay">
        <div class="loading-spinner"></div>
    </div>

    <div class="dashboard-container">
        
        {{-- Success Message --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Content Container --}}
        <div class="content-container">
            
            {{-- User Info Section --}}
            <div class="content-column">
                <div class="card user-info-card">
                    <div class="card-header-dashboard">
                        Profile
                    </div>
                    <div class="card-body">
                        <div class="user-info-item">
                            <div id="profile-picture-container" data-url="{{ route('profile.updatePicture') }}">
                                @if ($user->user_profile_picture)
                                    <img src="data:image/jpeg;base64,{{ base64_encode($user->user_profile_picture) }}" alt="Profile Picture" class="profile-img" id="profile-img">
                                @else
                                    <div class="profile-circle" id="profile-circle">
                                        <span>{{ strtoupper(substr($user->user_fname, 0, 1)) }}</span>
                                    </div>
                                @endif
                                <!-- Hidden File Input -->
                                <input type="file" name="user_profile_picture" id="user_profile_picture" class="d-none" accept="image/*">
                            </div>
                        </div>

                        <div class="user-info-item">
                            <strong>Email:</strong> <span>{{ $user->email }}</span>
                        </div>

                        <div class="user-info-item">
                            <strong>Ministry:</strong> <span>{{ $user->user_ministry }}</span>
                        </div>

                        <div>
                            <a href="{{ route('profile.edit') }}" class="btn custom-btn">Edit Profile</a>
                        </div>
                    </div>
                </div>
                
                {{-- Pending Member Approval Section --}}
                @if ($pendingRequests->isEmpty())
                    <div class="card">
                        <div class="card-header-dashboard">
                            <h3>No Pending Member Approvals</h3>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                There are no members currently waiting for approval.
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card">
                        <div class="card-header-dashboard">
                            Pending Member Approvals:
                        </div>
                        <div class="card-body">
                            <ul class="member-list">
                                @foreach ($pendingRequests as $request)
                                    @php
                                        $pendingMember = \App\Models\User::find($request->user_id);
                                    @endphp
                                    <li class="approval-list">
                                        <div class="approval-list-member-info">
                                            @if ($pendingMember->user_profile_picture)
                                                <img src="data:image/jpeg;base64,{{ base64_encode($pendingMember->user_profile_picture) }}" alt="Profile Picture" class="profile-img-nav">
                                            @else
                                                <div class="members-profile-circle">
                                                    <span>{{ strtoupper(substr($pendingMember->user_fname, 0, 1)) }}</span>
                                                </div>
                                            @endif
                                            - {{ $pendingMember->user_fname }} {{ $pendingMember->user_lname }} ({{ $pendingMember->email }})
                                        </div>
                                        <div>
                                            <form action="{{ route('dgroup.approve') }}" method="POST" style="display: inline;">
                                                @csrf
                                                <input type="hidden" name="email" value="{{ $pendingMember->email }}">
                                                <input type="hidden" name="token" value="{{ $request->approval_token }}">
                                                <input type="hidden" name="dgroupLeaderId" value="{{ Auth::user()->id }}">
                                                <input type="hidden" name="id" value="{{ $request->id }}"> <!-- Hidden form field for the id -->
                                                <button type="submit" class="btn custom-btn-dashboard">Approve</button>
                                            </form> 
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>        
                @endif
            </div>

            {{-- D-Group Leader Info Section --}}
            <div class="content-column">
                <div class="card">
                    <div class="card-header-dashboard">
                        D-Group Leader:
                    </div>
                    <div class="card-body">
                        @if ($user->user_dgroup_leader)
                            @php
                                $dgroupLeader = \App\Models\User::find($user->user_dgroup_leader);
                            @endphp
                            @if ($dgroupLeader)
                                <div class="user-info-item">
                                    <div class="leader-info">
                                        @if ($dgroupLeader->user_profile_picture)
                                            <img src="data:image/jpeg;base64,{{ base64_encode($dgroupLeader->user_profile_picture) }}" alt="Profile Picture" class="profile-img-nav">
                                        @else
                                            <div class="members-profile-circle">
                                                <span>{{ strtoupper(substr($dgroupLeader->user_fname, 0, 1)) }}</span>
                                            </div>
                                        @endif
                                        <span>-</span>
                                        <span>{{ $dgroupLeader->user_fname }} {{ $dgroupLeader->user_lname }} ({{ $dgroupLeader->email }})</span>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    D-Group leader information is not available.
                                </div>
                            @endif
                        @else
                            <div class="alert alert-info">
                                You are not a part of a D-Group, Join now!
                            </div>
                        @endif
                    </div>
                </div>

                {{-- D-Group Members Section --}}
                @if ($dgroupMembers->isEmpty())
                    <div class="card">
                        <div class="card-header-dashboard">
                            D-Group Members:
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                Give your email address to your D-Group members and ask them to register on our WOTG app. 
                                They should enter your email in the D-Group leader’s email field. 
                                Once they register, check your email for their request and approve it to complete the verification process.
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card">
                        <div class="card-header-dashboard">
                            D-Group Members:
                        </div>
                        <div class="card-body">
                            <ul class="member-list">
                                @foreach ($dgroupMembers as $member)
                                    <li>
                                        @if ($member->user_profile_picture)
                                            <img src="data:image/jpeg;base64,{{ base64_encode($member->user_profile_picture) }}" alt="Profile Picture" class="profile-img-nav">
                                        @else
                                            <div class="members-profile-circle">
                                                <span>{{ strtoupper(substr($member->user_fname, 0, 1)) }}</span>
                                            </div>
                                        @endif
                                        - {{ $member->user_fname }} {{ $member->user_lname }} ({{ $member->email }})
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- Dashboard JavaScript --}}
    <script src="{{ asset('js/dashboard.js?v=3.3') }}"></script>
@endsection
