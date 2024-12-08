<!-- partials.posts.blade.php -->
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

    <!-- Modal for likers -->
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

    <!-- Modal for comments -->
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
</div>