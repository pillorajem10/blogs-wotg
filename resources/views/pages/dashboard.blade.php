@extends('layouts.layout')

@section('title', 'Dashboard')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css?v=1.7') }}">
@endsection

@section('content')
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Welcome, {{ $user->user_fname }}!</h1>
        </div>

        <div class="user-info">
            <div class="user-info-item">
                <strong>Email:</strong> <span>{{ $user->email }}</span>
            </div>
            <div class="user-info-item">
                <strong>Role:</strong> <span>{{ $user->user_role }}</span>
            </div>
            <div class="user-info-item">
                <strong>Ministry:</strong> <span>{{ $user->user_ministry }}</span>
            </div>
        </div>

        <div class="additional-info">
            <h3>Your Details</h3>
            <div class="additional-info-item">
                <strong>Church Name:</strong> <span>{{ $user->user_church_name }}</span>
            </div>
            <div class="additional-info-item">
                <strong>Birthday:</strong> <span>{{ \Carbon\Carbon::parse($user->user_birthday)->format('F j, Y') }}</span>
            </div>
            <div class="additional-info-item">
                <strong>City:</strong> <span>{{ $user->user_city }}</span>
            </div>
        </div>

        <div class="actions">
            {{--<a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profile</a>--}}
            <form action="{{ route('auth.logout') }}" method="POST" class="logout-form">
                @csrf
                <button type="submit" class="btn btn-danger">Logout</button>
            </form>
        </div>
    </div>
@endsection
