@extends('layouts.authLayout')

@section('title', 'Sign Up')

@section('content')
    <div id="loading-overlay" class="loading-overlay">
        <div class="loading-spinner"></div>
    </div>
    <div>
        <h2 class="auth-title">Sign Up</h2>

        {{-- Show any session errors (like success or error) --}}
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        {{-- Show validation errors for each form field --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" id="profile-auth-form">
            @csrf
            <div class="signup-form">
                <div>
                    <!-- First Column -->
                    <div class="form-group">
                        <label for="user_fname">First Name</label>
                        <input type="text" name="user_fname" id="user_fname" class="form-input" value="{{ old('user_fname') }}" required>
                        @if ($errors->has('user_fname'))
                            <span class="error">{{ $errors->first('user_fname') }}</span>
                        @endif
                    </div>
            
                    <div class="form-group">
                        <label for="user_lname">Last Name</label>
                        <input type="text" name="user_lname" id="user_lname" class="form-input" value="{{ old('user_lname') }}" required>
                        @if ($errors->has('user_lname'))
                            <span class="error">{{ $errors->first('user_lname') }}</span>
                        @endif
                    </div>
            
                    <div class="form-group">
                        <label for="user_nickname">Nick Name</label>
                        <input type="text" name="user_nickname" id="user_nickname" class="form-input" value="{{ old('user_nickname') }}">
                        @if ($errors->has('user_nickname'))
                            <span class="error">{{ $errors->first('user_nickname') }}</span>
                        @endif
                    </div>
            
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-input" value="{{ old('email') }}" required>
                        @if ($errors->has('email'))
                            <span class="error">{{ $errors->first('email') }}</span>
                        @endif
                    </div>
            
                    <div class="form-group">
                        <label for="user_gender">Gender</label>
                        <select name="user_gender" id="user_gender" class="form-input" required>
                            <option value="" disabled selected>Select Gender</option>
                            <option value="male" {{ old('user_gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('user_gender') == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                        @if ($errors->has('user_gender'))
                            <span class="error">{{ $errors->first('user_gender') }}</span>
                        @endif
                    </div>
            
                    <div class="form-group">
                        <label for="user_mobile_number">Mobile Number</label>
                        <input type="text" name="user_mobile_number" id="user_mobile_number" class="form-input" value="{{ old('user_mobile_number') }}">
                        @if ($errors->has('user_mobile_number'))
                            <span class="error">{{ $errors->first('user_mobile_number') }}</span>
                        @endif
                    </div>
            
                    <div class="form-group">
                        <label for="user_birthday">Birthday</label>
                        <input type="date" name="user_birthday" id="user_birthday" class="form-input" value="{{ old('user_birthday') }}" required>
                        @if ($errors->has('user_birthday'))
                            <span class="error">{{ $errors->first('user_birthday') }}</span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-input" required>
                        @if ($errors->has('password'))
                            <span class="error">{{ $errors->first('password') }}</span>
                        @endif
                    </div>                    
                </div>
                
                <div>
                    <!-- Second Column -->
                    <div class="form-group">
                        <label for="user_country">Country</label>
                        <input type="text" name="user_country" id="user_country" class="form-input" value="{{ old('user_country') }}" required>
                        @if ($errors->has('user_country'))
                            <span class="error">{{ $errors->first('user_country') }}</span>
                        @endif
                    </div>
            
                    <div class="form-group">
                        <label for="user_city">City/Province</label>
                        <input type="text" name="user_city" id="user_city" class="form-input" value="{{ old('user_city') }}" required>
                        @if ($errors->has('user_city'))
                            <span class="error">{{ $errors->first('user_city') }}</span>
                        @endif
                    </div>
            
                    <div class="form-group">
                        <label for="user_church_name">Church Name</label>
                        <input type="text" name="user_church_name" id="user_church_name" class="form-input" value="{{ old('user_church_name') }}" required>
                        @if ($errors->has('user_church_name'))
                            <span class="error">{{ $errors->first('user_church_name') }}</span>
                        @endif
                    </div>
            
                    <div class="form-group">
                        <label for="user_already_a_dgroup_member">Are you a D-Group member?</label>
                        <select name="user_already_a_dgroup_member" id="user_already_a_dgroup_member" class="form-input" required>
                            <option value="" disabled selected>Select Yes or No</option>
                            <option value="1" {{ old('user_already_a_dgroup_member') == '1' ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ old('user_already_a_dgroup_member') == '0' ? 'selected' : '' }}>No</option>
                        </select>
                        @if ($errors->has('user_already_a_dgroup_member'))
                            <span class="error">{{ $errors->first('user_already_a_dgroup_member') }}</span>
                        @endif
                        <!-- D-Group Member Note -->
                        <div id="dgroup-member-note">
                            <p>If you are a dgroup member, please provide your dgroup leader’s email to complete your registration.</p>
                        </div>
                    </div>                    
            
                    <div class="form-group">
                        <label for="user_already_a_dgroup_leader">Are you a D-Group Leader?</label>
                        <select name="user_already_a_dgroup_leader" id="user_already_a_dgroup_leader" class="form-input" required>
                            <option value="" disabled selected>Select Yes or No</option>
                            <option value="1" {{ old('user_already_a_dgroup_leader') == '1' ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ old('user_already_a_dgroup_leader') == '0' ? 'selected' : '' }}>No</option>
                        </select>
                        @if ($errors->has('user_already_a_dgroup_leader'))
                            <span class="error">{{ $errors->first('user_already_a_dgroup_leader') }}</span>
                        @endif
                        <!-- D-Group Leader Note -->
                        <div id="dgroup-leader-note">
                            <p>To verify your account, give your email address to your dgroup members and ask them to register on our WOTG app. They should enter your email in the dgroup leader’s email field. Once they register, check your email for their request and approve it to complete the verification process.</p>
                        </div>
                    </div>
                    
            
                    <div class="form-group">
                        <label for="user_dgroup_leader">D-Group Leader's Email</label>
                        <input type="text" name="user_dgroup_leader" id="user_dgroup_leader" class="form-input" value="{{ old('user_dgroup_leader') }}">
                        @if ($errors->has('user_dgroup_leader'))
                            <span class="error">{{ $errors->first('user_dgroup_leader') }}</span>
                        @endif
                    </div>
            
                    <div class="form-group">
                        <label for="user_ministry">What ministry are you part of?</label>
                        <select name="user_ministry" id="user_ministry" class="form-input" required>
                            <option value="" disabled selected>Select a Ministry</option>
                            <option value="Music Ministry" {{ old('user_ministry') == 'Music Ministry' ? 'selected' : '' }}>Music Ministry</option>
                            <option value="Intercessory" {{ old('user_ministry') == 'Intercessory' ? 'selected' : '' }}>Intercessory</option>
                            <option value="Worship Service" {{ old('user_ministry') == 'Worship Service' ? 'selected' : '' }}>Worship Service</option>
                            <option value="Digital Missionary" {{ old('user_ministry') == 'Digital Missionary' ? 'selected' : '' }}>Digital Missionary</option>
                            <option value="Creatives and Communication" {{ old('user_ministry') == 'Creatives and Communication' ? 'selected' : '' }}>Creatives and Communication</option>
                            <option value="Admin" {{ old('user_ministry') == 'Admin' ? 'selected' : '' }}>Admin</option>
                            <option value="D-Group Management" {{ old('user_ministry') == 'D-Group Management' ? 'selected' : '' }}>D-Group Management</option>
                            <option value="None" {{ old('user_ministry') == 'None' ? 'selected' : '' }}>None</option>
                        </select>
                        @if ($errors->has('user_ministry'))
                            <span class="error">{{ $errors->first('user_ministry') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            

            <div class="form-group">
                <button type="submit" class="auth-button">Create Account</button>
            </div>
        </form>

        <div style="margin-top: 20px; text-align: center;">
            <p>Already got an account? <a href="{{ route('auth.login') }}">Login here</a>.</p>
        </div>
        <div style="margin-top: 20px; text-align: center;">
            <p><a href="{{ route('posts.index') }}">Go back to home</a></p>
        </div>

        <script src="{{ asset('js/auth.js?v=5.2') }}"></script>
    </div>
@endsection
