@extends('layouts.layout')

@section('title', 'Community')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/posts.css?v=7.0') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
@endsection

@section('content')
    <div id="loading-overlay" class="loading-overlay">
        <div class="loading-spinner"></div>
    </div>

    <div class="container">
        @auth
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
        @endauth
    

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
                    
                    <!-- Caption Input -->
                    <div class="form-group">
                        <label for="post_caption">Caption</label>
                        <textarea id="post_caption" name="post_caption" class="form-control" placeholder="What's on your mind?" rows="5" required></textarea>
                    </div>                    
        
                    <!-- URL Input -->
                    <div class="form-group">
                        <label for="post_link">Shared Link</label>
                        <input type="url" id="post_link" name="post_link" class="form-control" placeholder="Paste the URL here">
                    </div>
                    
                    <!-- File Upload Input -->
                    <div class="form-group">
                        <label for="posts_file_path">Upload Files (optional)</label>
                        <input type="file" id="posts_file_path" name="posts_file_path[]" class="form-control" multiple>
                        
                        <!-- File Previews -->
                        <div id="file_preview" style="margin-top: 10px;">
                            <!-- Previews will be dynamically added here -->
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
