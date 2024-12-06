@extends('layouts.layout')

@section('title', $blog->blog_title)

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/blogDetails.css?v=3.2') }}">
@endsection

@section('content')
    <div class="blog-details-container">
        <div class="blog-meta d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <span class="blog-date">
                    Date Added: {{ \Carbon\Carbon::parse($blog->blog_release_date_and_time)->format('F j, Y') }}
                </span>
            </div>
        </div>     

        <div class="blog-content">
            <div class="blog-thumbnail">
                @if($blog->blog_thumbnail)
                    <img src="data:image/jpeg;base64,{{ base64_encode($blog->blog_thumbnail) }}" alt="{{ $blog->blog_title }}" class="thumbnail-image">
                @else
                    <p class="no-thumbnail">No Thumbnail Available</p>
                @endif
            </div>

            <div class="blog-body">
                <div class="body-text">{!! $blog->blog_body !!}</div>
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="action-btns">
            <!-- Prev Button -->
            @if($prevBlog)
                <a href="{{ route('blogs.show', $prevBlog->id) }}" class="action-btn">&lt; Prev</a>
            @else
                <span class="action-btn disabled">&lt; Prev</span>
            @endif
        
            <!-- Back to Blogs Button -->
            <a href="{{ route('blogs.index') }}" class="action-btn">Back to Blogs</a>
        
            <!-- Next Button -->
            @if($nextBlog)
                <a href="{{ route('blogs.show', $nextBlog->id) }}" class="action-btn">Next &gt;</a>
            @else
                <span class="action-btn disabled">Next &gt;</span>
            @endif
        </div>        

        <!-- Comments Section -->
        <div class="comments-section">
            <h3 class="comments-title">Comments</h3>
        
            <!-- Check if the user is authenticated to comment -->
            @if(Auth::check())
                <!-- Comment Form -->
                <form action="{{ route('blogs.writeComment', $blog->id) }}" method="POST" class="comment-form">
                    @csrf
                    <textarea name="comment_body" placeholder="Write your comment..." rows="4" class="comment-textarea" required></textarea>
                    <button type="submit" class="btn btn-primary comment-btn">Post Comment</button>
                </form>
            @else
                <p class="login-prompt">Please <a href="{{ route('auth.login') }}" class="login-link">log in</a> to post a comment.</p>
            @endif
        
            <!-- Display Comments -->
            <div class="comment-list">
                @foreach($blog->comments as $comment)
                    <div class="comment">
                        <div class="comment-author">
                            <strong class="comment-author-name">
                                {{ $comment->user->user_fname }} {{ $comment->user->user_lname }}
        
                                <!-- Show "(Me)" if the comment is by the authenticated user -->
                                @if(Auth::check() && Auth::user()->id == $comment->comment_userid)
                                    <span class="me-label">(Me)</span>
                                @endif
                            </strong>
                            <span class="comment-date">Posted on {{ \Carbon\Carbon::parse($comment->created_at)->format('F j, Y') }}</span>
                        </div>
                        <p class="comment-body">{{ $comment->comment_body }}</p>
                    </div>
                @endforeach
            </div>
        </div>               
    </div>
@endsection
