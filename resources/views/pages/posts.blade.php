@extends('layouts.layout')

@section('title', 'Community')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/posts.css?v=2.9') }}">
@endsection

@section('content')
    <div id="loading-overlay" class="loading-overlay">
        <div class="loading-spinner"></div>
    </div>

    <div class="container">
        <!-- Button to open modal -->
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

        <!-- Modal structure -->
        <div id="addPostModal" class="modal">
            <div class="modal-content">
                <span id="closeModalBtn" class="close">&times;</span>
                <h3>Create Post</h3>
                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
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
                    <div class="post-card">
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

                        {{--<div class="post-footer">
                            <div class="post-actions">
                                <button class="like-btn">Like (Coming Soon)</button>
                                <button class="comment-btn">Comment (Coming Soon)</button>
                            </div>
                            <div class="post-stats">
                                <span class="likes-count">{{ $post->post_likes }} Likes</span>
                                <span class="comments-count">{{ is_array($post->post_comments) ? count($post->post_comments) : 0 }} Comments</span>
                            </div>
                        </div>--}}
                    </div>
                @empty
                    <div class="no-posts">
                        <p>No posts available.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script src="{{ asset('js/posts.js?v=2.9') }}"></script>
@endsection
