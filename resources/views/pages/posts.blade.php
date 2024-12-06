@extends('layouts.layout')

@section('title', 'Community')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/posts.css?v=3.4') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
@endsection

@section('content')
    <div id="loading-overlay" class="loading-overlay">
        <div class="loading-spinner"></div>
    </div>

    <div class="container">
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
                        <input type="text" id="post_caption" name="post_caption" class="form-control" placeholder="What's on your mind?" required>
                    </div>

                    <div class="form-group">
                        <label for="post_image">Image (optional)</label>
                        <input type="file" id="post_image" name="post_image" class="form-control">
                    </div>

                    <button type="submit" class="btn-submit">Post</button>
                </form>
            </div>
        </div>

        <!-- Posts Feed -->
        <div class="posts-feed">
            <div>
                @forelse ($posts as $post)
                    <div class="post-card" id="post-{{ $post->id }}">
                        <hr>
                        <div class="post-header">
                            <div class="post-user">
                                <div class="user-avatar">
                                    @if ($post->user->user_profile_picture) 
                                        <img src="data:image/jpeg;base64,{{ base64_encode($post->user->user_profile_picture) }}" alt="User Avatar">
                                    @else
                                        <div class="members-profile-circle-feed" id="profile-circle">
                                            <span>{{ strtoupper(substr($post->user->user_fname, 0, 1)) }}</span>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="user-info">
                                    <span class="user-name">{{ $post->user->user_fname }} {{ $post->user->user_lname }}</span>
                                    <span>-</span>
                                    <span class="post-time">{{ \Carbon\Carbon::parse($post->created_at)->format('F j, Y g:i A') }}</span>
                                </div>
                            </div>
                        </div>
        
                        <div class="post-content">
                            <h2 class="post-caption">{{ $post->post_caption }}</h2>
                            @if ($post->post_image)
                                <div class="post-image">
                                    <img src="data:image/jpeg;base64,{{ base64_encode($post->post_image) }}" alt="Post Image" class="img-fluid">
                                </div>
                            @endif
                        </div>
        
                        <div class="post-footer">
                            <div class="post-actions">
                                <!-- Check if the user has liked the post -->
                                <button class="like-btn" data-post-id="{{ $post->id }}">
                                    <!-- Heart icon, toggles between filled and empty depending on if the post is liked -->
                                    <i class="fa fa-heart fa-lg"></i>
                                    <span>{{ $post->likedByUser ? 'Liked' : 'Like' }}</span>
                                </button>
                                <span class="likes-count" id="likes-count-{{ $post->id }}">{{ $post->likes()->count() }}</span>
                            </div>
                            {{--<div class="post-stats">
                                <span class="likes-count" id="likes-count-{{ $post->id }}">{{ $post->likes()->count() }} Likes</span>
                            </div>--}}
                        </div> 
                        <hr>                       
                    </div>
                @empty
                    <div class="no-posts">
                        <p>No posts available.</p>
                    </div>
                @endforelse
            </div>
        </div>
            
    </div>

    <script src="{{ asset('js/posts.js?v=3.4') }}"></script>
@endsection
