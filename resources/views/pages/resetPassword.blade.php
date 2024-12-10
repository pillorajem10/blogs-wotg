@extends('layouts.authLayout')

@section('title', 'Reset Password')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/resetPassword.css?v=2.2') }}">
@endsection

@section('content')
    <div class="reset-password-container">
        <h2>Reset Your Password</h2>
        <p>Enter a new password below to reset your password.</p>

        <!-- Show error messages if available -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Reset password form -->
        <form method="POST" action="{{ route('password.reset') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group">
                <label for="password">New Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

            <div class="form-group">
                <button type="submit" class="auth-button">Reset Password</button>
            </div>
        </form>

        <!-- Go back to login link -->
        <div class="go-back-link" style="text-align: center; margin-top: 20px;">
            <p>Remembered your password? <a href="{{ route('auth.login') }}">Go back to login</a></p>
        </div>
    </div>
@endsection
