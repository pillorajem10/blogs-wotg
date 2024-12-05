@extends('layouts.layout')

@section('title', 'Dashboard')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css?v=2.2') }}">
@endsection

@section('content')
    <div class="dashboard-container">

        {{-- Success Message --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Dashboard Header --}}
        <div class="dashboard-header">
            <h1>Welcome, {{ $user->user_fname }}!</h1>
        </div>

        {{-- User Info Card --}}
        <div class="card user-info-card">
            <div class="card-header">
                <h2>Your Information</h2>
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

        {{-- D-Group Leader Info --}}
        <div class="card dgroup-leader-card">
            <div class="card-header">
                <h3>D-Group Leader:</h3>
            </div>
            <div class="card-body">
                @if ($user->user_dgroup_leader)
                    @php
                        // Find the D-Group leader by their ID
                        $dgroupLeader = \App\Models\User::find($user->user_dgroup_leader);
                    @endphp
                    @if ($dgroupLeader)
                        <div class="user-info-item">
                            <strong>Name:</strong> <span>{{ $dgroupLeader->user_fname }} {{ $dgroupLeader->user_lname }}</span>
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
        @if($dgroupMembers->isEmpty())
            <div class="card dgroup-members-card">
                <div class="card-header">
                    <h3>D-Group Members:</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        Give your email address to your dgroup members and ask them to register on our WOTG app. 
                        They should enter your email in the dgroup leader’s email field. 
                        Once they register, check your email for their request and approve it to complete the verification process.
                    </div>
                </div>
            </div>
        @else
            <div class="card dgroup-members-card">
                <div class="card-header">
                    <h3>D-Group Members:</h3>
                </div>
                <div class="card-body">
                    <ul class="member-list">
                        @foreach ($dgroupMembers as $member)
                            <li>{{ $member->user_fname }} {{ $member->user_lname }} ({{ $member->email }})</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <script src="{{ asset('js/dashboard.js?v=2.2') }}"></script>
    </div>
@endsection
