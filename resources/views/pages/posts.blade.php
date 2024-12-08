@extends('layouts.layout')

@section('title', 'Community')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/posts.css?v=4.5') }}">
    <link rel="stylesheet" href="{{ asset('css/blogs.css?v=4.5') }}">
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
                
                    <!-- Error messages for the form -->
                
                    <div class="form-group">
                        <label for="post_caption">Caption</label>
                        <textarea id="post_caption" name="post_caption" class="form-control" placeholder="What's on your mind?" rows="5" required></textarea>
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

        <div class="carousel">
            <div class="card-container">
                @forelse ($blogs as $blog)
                    <div class="blog-card">
                        <div class="blog-card-body">
                            @if($blog->blog_thumbnail)
                                <a href="{{ route('blogs.show', $blog->id) }}">
                                    <img src="data:image/jpeg;base64,{{ base64_encode($blog->blog_thumbnail) }}" alt="{{ $blog->blog_title }}" class="blog-thumbnail">
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center">No blogs found.</div>
                @endforelse
            </div>
        </div>  
        
        <div class="pagination-controls">
            <button id="loadMore" style="display: none;">Load More</button>
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
                            <h2 class="post-caption" id="caption-{{ $post->id }}">
                                <span class="caption-short">
                                    {{ \Str::limit($post->post_caption, 500) }}
                                </span>
                                <span class="caption-full" style="display: none;">
                                    {{ $post->post_caption }}
                                </span>
                                @if (strlen($post->post_caption) > 500)
                                    <a href="javascript:void(0);" class="see-more" onclick="toggleSeeMore({{ $post->id }})">... See More</a>
                                @endif
                            </h2>
        
                            @if ($post->post_image)
                                <div class="post-image">
                                    <img src="data:image/jpeg;base64,{{ base64_encode($post->post_image) }}" alt="Post Image" class="img-fluid">
                                </div>
                            @endif
                        </div>
        
                        @auth
                            <div class="post-footer">
                                <div class="post-actions">
                                    <!-- Like button -->
                                    <button class="like-btn" data-post-id="{{ $post->id }}">
                                        <i class="fa fa-heart fa-lg"></i>
                                        <span>{{ $post->likedByUser ? 'Liked' : 'Like' }}</span>
                                    </button>
            
                                    <!-- Likes Count -->
                                    <span class="likes-count" id="likes-count-{{ $post->id }}" onclick="showLikersModal({{ $post->id }})">
                                        {{ $post->likes->count() }}
                                    </span>
            
                                    <button class="comment-btn" data-post-id="{{ $post->id }}">
                                        <i class="fa fa-comment fa-lg"></i>
                                        <span>Comments</span>
                                    </button>
                                    <!-- Comments count -->
                                    <span class="comments-count" id="comments-count-{{ $post->id }}">{{ $post->comments->count() }}</span>
            
                                    <!-- Delete Post -->
                                    @if ($post->post_user_id == auth()->id())
                                        <form action="{{ route('post.delete', $post->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Are you sure you want to delete this post?')" type="submit" class="delete-btn">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                                <span>Delete</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endauth
                        <hr>

                        <div id="modal-likers-{{ $post->id }}" class="modal">
                            <div class="modal-content">
                                <span class="close" data-post-id="{{ $post->id }}" onclick="closeLikersModal({{ $post->id }})">&times;</span>
                                <h4>People Who Liked This Post</h4>
                                <div class="likers-list" id="likers-list-{{ $post->id }}">
                                    @foreach ($post->likes as $like)
                                        <div class="liker">
                                            <div class="user-info-liker">
                                                <div class="comment-avatar">
                                                    @if ($like->user->user_profile_picture)
                                                        <img src="data:image/jpeg;base64,{{ base64_encode($like->user->user_profile_picture) }}" alt="User Avatar">
                                                    @else
                                                        <div class="profile-circle-comment">
                                                            <span>{{ strtoupper(substr($like->user->user_fname, 0, 1)) }}</span>
                                                        </div>
                                                    @endif 
                                                </div>
                                                <span>{{ $like->user->user_fname }} {{ $like->user->user_lname }}</span>
                                            </div>
    
                                            <div>
                                                <i class="fa fa-heart fa-lg"></i>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
        
                        <!-- Modal Structure -->
                        <div id="commentModal-{{ $post->id }}" class="modal">
                            <div class="modal-content">
                                <span class="close" data-post-id="{{ $post->id }}">&times;</span>
                                
                                <!-- Existing Comments Section -->
                                <div class="comments-list" id="comments-list-{{ $post->id }}">
                                    @foreach ($post->comments as $comment)
                                        <div class="comment">
                                            <div class="comment-avatar">
                                                @if ($comment->user->user_profile_picture)
                                                    <img src="data:image/jpeg;base64,{{ base64_encode($comment->user->user_profile_picture) }}" alt="User Avatar">
                                                @else
                                                    <div class="profile-circle-comment">
                                                        <span>{{ strtoupper(substr($comment->user->user_fname, 0, 1)) }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="comment-body">
                                                <div class="comment-author">
                                                    <strong>{{ $comment->user->user_fname }} {{ $comment->user->user_lname }}</strong>
                                                    <span class="comment-time">{{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}</span>
                                                </div>
                                                <p class="comment-text">{{ $comment->comment_text }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                <hr>
                                
                                <!-- Comment Input -->
                                <textarea id="comment-text-{{ $post->id }}" class="form-control" placeholder="Write a comment..." rows="3"></textarea>
                                <button class="btn-submit-post mt-2" onclick="addComment({{ $post->id }})">Submit</button>
                            </div>
                        </div>                                                        
                        <!-- End Modal Structure -->
                    </div>
                @empty
                    <div class="no-posts">
                        <p>No posts available.</p>
                    </div>
                @endforelse
            </div>
        </div>        
            
    </div>

    <script src="{{ asset('js/posts.js?v=4.5') }}"></script>
    <script>
        window.postsUrl = "{{ route('posts.index') }}";
    </script>    
@endsection
