@extends('layouts.authLayout')

@section('title', 'Sign Up Seeker')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/signUpSeeker.css?v=7.4') }}">
    <div class="signup-container">
        <h2 class="signup-title">Become part of our community.</h2>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('subscribers.signup.submit') }}" method="POST" class="custom-signup-form">
            @csrf
            <div class="signup-form">
                <div>
                    <div class="form-group">
                        <label for="sub_fname">First Name</label>
                        <input type="text" name="sub_fname" id="sub_fname" class="form-input" required>
                    </div>
        
                    <div class="form-group">
                        <label for="sub_lname">Last Name</label>
                        <input type="text" name="sub_lname" id="sub_lname" class="form-input" required>
                    </div>
    
                    <div class="form-group">
                        <label for="sub_gender">Gender</label>
                        <select name="sub_gender" id="sub_gender" class="form-input" required>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="sub_age">Age</label>
                        <input type="text" name="sub_age" id="sub_age" class="form-input" required>
                    </div>
                </div>
    
                <div>
                    <div class="form-group">
                        <label for="sub_email">Email</label>
                        <input type="email" name="sub_email" id="sub_email" class="form-input" required>
                    </div>
        
                    <div class="form-group">
                        <label for="sub_country">Country</label>
                        <input type="text" name="sub_country" id="sub_country" class="form-input" required>
                    </div>
        
                    <div class="form-group">
                        <label for="sub_city">City</label>
                        <input type="text" name="sub_city" id="sub_city" class="form-input" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="auth-button">Register</button>
            </div>
        </form>

        <!-- Back to Blogs Button -->
        <div class="text-center">
            <a href="{{ route('posts.index') }}" class="back-to-blogs-btn">Back to Blogs</a>
        </div>

        <script src="{{ asset('js/signupSeeker.js?v=7.4') }}"></script>
    </div>
@endsection
