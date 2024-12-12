@extends('layouts.layout')

@section('title', 'Profile')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/posts.css?v=7.0') }}">
    <link rel="stylesheet" href="{{ asset('css/userProfile.css?v=7.0') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
@endsection

<div class="profile-banner-container">
    <div class="profile-banner" id="profileBanner">
        @if ($user->user_profile_banner)
            <img src="{{ asset($user->user_profile_banner) }}" alt="User Banner" class="profile-banner-image" id="bannerImage">
        @else
            <div class="profile-banner-default">
                <span>Cover Photo</span>
            </div>
        @endif
    </div>

    <!-- Show the file input only if the user is the owner of the profile -->
    @if (Auth::id() === $user->id)
        <!-- Hidden file input for uploading banner -->
        <form id="bannerForm" action="{{ route('profile.updateBanner') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="profile_banner" id="profileBannerInput" style="display: none;" accept="image/*">
        </form>
    @endif

    @if (Auth::id() === $user->id)
        <script>
            document.getElementById('profileBanner').addEventListener('click', function () {
                // Programmatically trigger the file input click event
                document.getElementById('profileBannerInput').click();
            });

            // Submit the form when a file is selected
            document.getElementById('profileBannerInput').addEventListener('change', function () {
                document.getElementById('bannerForm').submit();
            });
        </script>
    @endif

    <div class="profile-info">
        <div class="user-avatar-profile">
            @if ($user->user_profile_picture) 
                <img src="data:image/jpeg;base64,{{ base64_encode($user->user_profile_picture) }}" alt="User Avatar" class="avatar-image">
            @else
                <div class="user-avatar-profile-circle" id="profile-circle">
                    <span>{{ strtoupper(substr($user->user_fname, 0, 1)) }}</span>
                </div>
            @endif
        </div>
        <span class="profile-info-name">{{ $user->user_fname }} {{ $user->user_lname }}</span>
    </div>
</div>


@section('content')
    <div id="loading-overlay" class="loading-overlay">
        <div class="loading-spinner"></div>
    </div>

    <div class="container-user-profile">
        {{--@auth
            @if (auth()->id() == $user->id) <!-- Check if the logged-in user is viewing their own profile -->
                <hr>
                <div class="add-post-container">
                    <div class="user-avatar">
                        @if ($user->user_profile_picture) 
                            <img src="data:image/jpeg;base64,{{ base64_encode($user->user_profile_picture) }}" alt="User Avatar">
                        @else
                            <div class="members-profile-circle-feed" id="profile-circle">
                                <span>{{ strtoupper(substr($user->user_fname, 0, 1)) }}</span>
                            </div>
                        @endif
                    </div>
                    <button id="addPostBtn" class="add-post-btn">What's on your mind?</button>
                </div>
            @endif
        @endauth--}}
    
        @if ($errors->any())
            <div class="alert alert-danger mt-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Modal structure -->
        <div id="addPostModal" class="modal">
            <div class="modal-content">
                <span id="closeModalBtn" class="close">&times;</span>
                <h3>Create Post</h3>
        
                <!-- Form with error messages -->
                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Error messages for the form -->
                    
                    <div class="form-group">
                        <label for="post_caption">Caption</label>
                        <textarea id="post_caption" name="post_caption" class="form-control" placeholder="What's on your mind?" rows="5" required></textarea>
                    </div>                    
        
                    <!-- Input for URL -->
                    <div class="form-group">
                        <label for="post_link">Shared Link</label>
                        <input type="url" id="post_link" name="post_link" class="form-control" placeholder="Paste the URL here">
                    </div>
                    
                    <div class="form-group">
                        <label for="post_image">Image (optional)</label>
                        <input type="file" id="post_image" name="post_image" class="form-control" onchange="previewImage()">
                        
                        <!-- Image preview -->
                        <div id="image_preview" style="margin-top: 10px;">
                            <img id="preview" src="" alt="Image Preview" style="display: none; max-width: 100%; height: auto;">
                        </div>
                    </div>
                
                    <button type="submit" class="btn-submit-post">Post</button>
                </form>
            </div>
        </div>
          

        <!-- Posts Feed -->
        <div class="posts-feed" id="posts-container" data-next-page-url="{{ $posts->nextPageUrl() }}">
            <div>
                @include('partials.posts')
            </div>
        </div>
        
        <div id="loading-spinner" class="loading-spinner-infinite-scroll">
            <div class="spinner-infinite-scroll"></div>
        </div>
        
    </div>
    <script src="{{ asset('js/posts.js?v=7.0') }}"></script>
@endsection
