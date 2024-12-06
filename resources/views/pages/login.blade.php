@extends('layouts.authLayout')

@section('title', 'Login')

@section('content')
    <div id="loading-overlay" class="loading-overlay">
        <div class="loading-spinner"></div>
    </div>
    
    <div>
        <h2 class="auth-title">Login</h2>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
    
        <form action="{{ route('auth.login.submit') }}" method="POST" id="profile-auth-form">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-input" required>
            </div>
        
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-input" required>
            </div>
        
            <div class="form-group">
                <button type="submit" class="auth-button">Login</button>
            </div>
        </form>

        <div style="margin-top: 20px; text-align: center;">
            <p>Don't have an account? <a href="{{ route('signup') }}">Sign Up here</a>.</p>
        </div>
        <div style="margin-top: 20px; text-align: center;">
            <p><a href="{{ route('blogs.index') }}">Go Back to blogs</a></p>
        </div>

        <script src="{{ asset('js/auth.js?v=3.0') }}"></script>
    </div>
@endsection
