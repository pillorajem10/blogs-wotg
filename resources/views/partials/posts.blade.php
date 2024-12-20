@foreach ($posts as $post)
    <div class="post-card" id="post-{{ $post->id }}">
        <div class="post-header">
            <div class="post-user">
                <div class="user-avatar">
                    <a href="{{ route('profile.view', ['userId' => $post->user->id]) }}">
                        @if ($post->user->user_profile_picture)
                            <img src="data:image/jpeg;base64,{{ base64_encode($post->user->user_profile_picture) }}" alt="User Avatar">
                        @else
                            <div class="members-profile-circle-feed" id="profile-circle">
                                <span>{{ strtoupper(substr($post->user->user_fname, 0, 1)) }}</span>
                            </div>
                        @endif
                    </a>
                </div>

                <div class="user-info">
                    <span class="user-name">{{ $post->user->user_fname }} {{ $post->user->user_lname }}</span>
                    <span>-</span>
                    <span class="post-time">{{ \Carbon\Carbon::parse($post->created_at)->format('F j, Y g:i A') }}</span>
                </div>
            </div>
        </div>

        <div class="post-content">
            <!-- Caption Section -->
            <h2 class="post-caption" id="caption-{{ $post->id }}">
                <span class="caption-short">
                    {!! nl2br(e(Str::limit($post->post_caption, 500))) !!}
                </span>
                <span class="caption-full" style="display: none;">
                    {!! nl2br(e($post->post_caption)) !!}
                </span>
                @if (strlen($post->post_caption) > 500)
                    <a href="javascript:void(0);" class="see-more" onclick="toggleSeeMore({{ $post->id }})">... See More</a>
                @endif
            </h2>
        
            @if ($post->post_image)
                <div class="post-image">
                    <img src="data:image/jpeg;base64,{{ base64_encode($post->post_image) }}" alt="Post Image" class="img-fluid lazy modal-post-photo" loading="lazy" onclick="openModal('{{ base64_encode($post->post_image) }}')">
                </div>
            @endif
            
            @if (!empty($post->post_file_path) && is_array($post->post_file_path))
                <div class="post-images">
                    @foreach ($post->post_file_path as $index => $filePath)
                        @if ($index < 2) <!-- Show only the first 2 media files -->
                            <div class="post-image">
                                @php
                                    // Get file extension to determine if it's an image or video
                                    $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                                @endphp
                    
                                @if (in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'])) 
                                    <!-- If it's an image -->
                                    <img 
                                        src="{{ asset($filePath) }}?v={{ time() }}" 
                                        alt="Post Image" 
                                        class="img-fluid lazy modal-post-photo" 
                                        loading="lazy" 
                                        data-src="{{ asset($filePath) }}?v={{ time() }}" 
                                        onclick="openModal({{ $post->id }}, {{ $index }}, {{ json_encode($post->post_file_path) }})">
                                @elseif (in_array(strtolower($fileExtension), ['mp4', 'webm', 'ogg'])) 
                                    <!-- If it's a video -->
                                    <video 
                                        class="img-fluid lazy" 
                                        controls 
                                        loading="lazy" 
                                        data-src="{{ asset($filePath) }}?v={{ time() }}" 
                                        onclick="openModal({{ $post->id }}, {{ $index }}, {{ json_encode($post->post_file_path) }})">
                                        <source src="{{ asset($filePath) }}?v={{ time() }}" type="video/{{ $fileExtension }}">
                                        Your browser does not support the video tag.
                                    </video>
                                @endif
                    
                                @if ($index === 1 && count($post->post_file_path) > 2)
                                    <div class="media-overlay" onclick="openModal({{ $post->id }}, {{ $index }}, {{ json_encode($post->post_file_path) }})">
                                        +{{ count($post->post_file_path) - 2 }}
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        
            <!-- Embedded Content Section -->
            @if ($post->embeddedHtml)
                <div class="embedded-content">
                    {!! $post->embeddedHtml !!}
                </div>
            @endif
        </div>
        
        <div id="photoModal" class="modal-content-post-photo" style="display: none;">
            <span class="close" onclick="closeModal()">&times;</span>
            
            <!-- Wrapper for the image and buttons -->
            <div class="modal-photo-wrapper">
                <button class="prev" onclick="changeImage(-1)">&lt;</button>
                <img id="modalImage" src="" alt="Modal Photo" class="modal-photo">
                <button class="next" onclick="changeImage(1)">&gt;</button>
            </div>
        </div>
        

        @auth
            <div class="post-footer">
                <div class="post-actions">
                    <button class="comment-btn" data-post-id="{{ $post->id }}">
                        <i class="fa fa-comment"></i>
                        <span>Comments</span>
                    </button>                    
                    <span class="comments-count" id="comments-count-{{ $post->id }}">{{ $post->comments->count() }}</span>
                    <!-- Like Button -->
                    <div class="reactions">
                        <!-- Like Button -->
                        <button class="react-btn {{ $post->likedByUser ? 'active' : '' }}" data-post-id="{{ $post->id }}" data-reaction="like">
                            <i class="fa fa-thumbs-up fa-2x"></i> 
                        </button>
                        {{--<span class="react-count" id="likes-count-{{ $post->id }}" onclick="showLikersModal({{ $post->id }}, 'like')">
                            {{ $post->reactionCounts['like'] ?? 0 }}
                        </span>--}}
                    
                        <!-- Heart Button -->
                        <button class="react-btn {{ $post->reactedWithHeart ? 'active' : '' }}" data-post-id="{{ $post->id }}" data-reaction="heart">
                            <i class="fa fa-heart fa-2x"></i> 
                        </button>
                        {{--<span class="react-count" id="hearts-count-{{ $post->id }}" onclick="showLikersModal({{ $post->id }}, 'heart')">
                            {{ $post->reactionCounts['heart'] ?? 0 }}
                        </span>--}}
                    
                        <!-- Care Button -->
                        <button class="react-btn {{ $post->reactedWithCare ? 'active' : '' }}" data-post-id="{{ $post->id }}" data-reaction="care">
                            <i class="fa fa-laugh-squint fa-2x"></i>
                        </button>
                        {{--<span class="react-count" id="cares-count-{{ $post->id }}" onclick="showLikersModal({{ $post->id }}, 'care')">
                            {{ $post->reactionCounts['care'] ?? 0 }}
                        </span>--}}

                        <span class="react-count" id="all-reactions-count-{{ $post->id }}" onclick="showLikersModal({{ $post->id }})">
                            {{ $post->reactionCounts['all'] ?? 0 }}
                        </span>                        
                    </div>
                    @if ($post->post_user_id == auth()->id())
                        <form action="{{ route('post.delete', $post->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Are you sure you want to delete this post?')" type="submit" class="delete-btn">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                                <span>Delete</span>
                            </button>
                        </form>
                
                        <!-- Edit Post -->
                        <a href="{{ route('post.edit', ['postId' => $post->id]) }}" class="edit-btn ml-3">
                            <i class="fa fa-edit fa-lg"></i>
                            <span>Edit</span>
                        </a>
                    @endif
                </div>                               
            </div>
        @endauth

        <div id="modal-likers-{{ $post->id }}" class="modal" style="display: none;">
            <div class="modal-content">
                <span class="close" data-post-id="{{ $post->id }}" onclick="closeLikersModal({{ $post->id }})">&times;</span>
                
                <!-- Reaction Tabs -->
                <div class="reaction-tabs">
                    <button data-reaction="all" onclick="filterReactions('{{ $post->id }}', 'all')">All</button>
                    <button data-reaction="like" onclick="filterReactions('{{ $post->id }}', 'like')"><i class="fa fa-thumbs-up"></i></button>
                    <button data-reaction="heart" onclick="filterReactions('{{ $post->id }}', 'heart')"><i class="fa fa-heart"></i></button>
                    <button data-reaction="care" onclick="filterReactions('{{ $post->id }}', 'care')"><i class="far fa-laugh-squint"></i></button>
                </div>
                
        
                <div class="likers-list" id="likers-list-{{ $post->id }}">
                    <!-- Users who reacted will be dynamically injected here -->
                    @foreach ($post->likes as $like)
                        <div class="liker" data-reaction="{{ $like->reaction }}">
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
                                <!-- Display reaction icon -->
                                @if ($like->reaction == 'like')
                                    <i class="fa fa-thumbs-up fa-xl"></i>
                                @elseif ($like->reaction == 'heart')
                                    <i class="fa fa-heart fa-xl"></i>
                                @elseif ($like->reaction == 'care')
                                    <i class="fa fa-laugh-squint fa-xl"></i>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        
        

        <!-- Modal Structure -->
        <div id="commentModal-{{ $post->id }}" class="modal">
            <div class="modal-content">
                <span class="close" data-post-id="{{ $post->id }}" onclick="closeCommentsModal({{ $post->id }})">&times;</span>
                
                <!-- Existing Comments Section -->
                <div class="comments-list" id="comments-list-{{ $post->id }}">
                    @foreach ($post->comments as $comment)
                        <div class="comment" data-comment-id="{{ $comment->id }}">
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
                                
                                <!-- Reply Count & View Replies Button -->
                                <div class="comment-reply-section">
                                    <span class="reply-count" id="reply-count-{{ $comment->id }}">Replies: {{ $comment->replies->count() }}</span>
                                    <button class="btn-view-replies mt-2" onclick="toggleReplies({{ $comment->id }})">
                                        View Replies
                                    </button>
                                </div>                                                
                                
                                <button class="btn-reply mt-2" onclick="toggleReplyBox({{ $comment->id }})">
                                    <i class="fa fa-comment"></i>
                                    <span>Reply</span>
                                </button>

                                @if (auth()->id() === $comment->user->id)
                                    <button onclick="deleteComment({{ $comment->id }}, {{ $post->id }})" class="delete-btn mt-2">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                        <span>Delete</span>
                                    </button>
                                @endif
                                
                                <!-- Display Existing Replies -->
                                <div class="replies-list" id="replies-list-{{ $comment->id }}">
                                    @foreach ($comment->replies as $reply)
                                        <div class="reply">
                                            <div class="reply-avatar">
                                                @if ($reply->user->user_profile_picture)
                                                    <img src="data:image/jpeg;base64,{{ base64_encode($reply->user->user_profile_picture) }}" alt="User Avatar">
                                                @else
                                                    <div class="profile-circle-reply">
                                                        <span>{{ strtoupper(substr($reply->user->user_fname, 0, 1)) }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="reply-body">
                                                <div class="reply-author">
                                                    <strong>{{ $reply->user->user_fname }} {{ $reply->user->user_lname }}</strong>
                                                    <span class="reply-time">{{ \Carbon\Carbon::parse($reply->created_at)->diffForHumans() }}</span>
                                                </div>
                                                <p class="reply-text">{{ $reply->reply_text }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="reply-section" id="reply-section-{{ $comment->id }}" style="display: none;">
                                    <textarea id="reply-text-{{ $comment->id }}" class="form-control" placeholder="Write a reply..." rows="2"></textarea>
                                    <button class="btn-submit-reply mt-1" data-comment-id="{{ $comment->id }}">Submit Reply</button>
                                </div>
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

    <div class="d-none">
        {{ $posts->links() }}
    </div>
@endforeach