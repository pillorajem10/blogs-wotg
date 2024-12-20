<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostLike;
use App\Models\PostComment;
use App\Models\User;
use App\Models\PostCommentReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log; 


use Embed\Embed;
use Carbon\Carbon;
use App\Models\Blog;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Ensure the user is authenticated
    }

    /**
     * Display a listing of the posts.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $today = Carbon::now('Asia/Manila');
        
        // Load posts with likes, reactions, and pagination
        $posts = Post::with(['likes' => function ($query) {
            $query->select('post_id', 'reaction', 'user_id');
        }])
        ->orderBy('created_at', 'desc')
        ->paginate(3);
    
        // Map the posts for additional processing
        $posts->map(function ($post) {
            // Embed data processing
            if ($post->post_link) {
                $embed = new Embed();
                try {
                    $info = $embed->get($post->post_link);
                    $post->embeddedHtml = $info->code;
                } catch (\Exception $e) {
                    $post->embeddedHtml = null;
                }
            } else {
                $post->embeddedHtml = null;
            }
    
            // Count reactions for each type
            $reactionCounts = [
                'like' => $post->likes->where('reaction', 'like')->count(),
                'heart' => $post->likes->where('reaction', 'heart')->count(),
                'care' => $post->likes->where('reaction', 'care')->count(),
                'all' => $post->likes->count(), // Total sum of all reactions
            ];
            $post->reactionCounts = $reactionCounts;

            Log::info("Reaction counts for post ID {$post->id}:", $reactionCounts);
    
            // Check if the current user has reacted to this post
            $post->likedByUser = $post->likes->where('user_id', auth()->id())->where('reaction', 'like')->isNotEmpty();
            $post->reactedWithHeart = $post->likes->where('user_id', auth()->id())->where('reaction', 'heart')->isNotEmpty();
            $post->reactedWithCare = $post->likes->where('user_id', auth()->id())->where('reaction', 'care')->isNotEmpty();
    
            return $post;
        });
    
        $user = auth()->user();
    
        // Handle AJAX requests for infinite scroll
        if ($request->ajax()) {
            $view = view('partials.posts', compact('posts'))->render();
            return response()->json([
                'view' => $view,
                'nextPageUrl' => $posts->nextPageUrl(),
            ]);
        }
    
        // Return the main view
        return view('pages.posts', compact('posts', 'user'));
    }    
      
    
    public function viewProfile(Request $request, $userId)
    {
        // Get the user
        $user = User::findOrFail($userId);
        $today = Carbon::now('Asia/Manila');
        
        // Load posts for the specific user with likes, reactions, and pagination
        $posts = Post::with(['likes' => function ($query) {
            $query->select('post_id', 'reaction', 'user_id');
        }])
        ->where('post_user_id', $userId) // Filter posts by the user ID
        ->orderBy('created_at', 'desc')
        ->paginate(3);
    
        // Map the posts for additional processing
        $posts->map(function ($post) {
            // Embed data processing
            if ($post->post_link) {
                $embed = new Embed();
                try {
                    $info = $embed->get($post->post_link);
                    $post->embeddedHtml = $info->code;
                } catch (\Exception $e) {
                    $post->embeddedHtml = null;
                }
            } else {
                $post->embeddedHtml = null;
            }
    
            // Count reactions for each type
            $reactionCounts = [
                'like' => $post->likes->where('reaction', 'like')->count(),
                'heart' => $post->likes->where('reaction', 'heart')->count(),
                'care' => $post->likes->where('reaction', 'care')->count(),
            ];
            $post->reactionCounts = $reactionCounts;
    
            // Check if the current user has reacted to this post
            $post->likedByUser = $post->likes->where('user_id', auth()->id())->where('reaction', 'like')->isNotEmpty();
            $post->reactedWithHeart = $post->likes->where('user_id', auth()->id())->where('reaction', 'heart')->isNotEmpty();
            $post->reactedWithCare = $post->likes->where('user_id', auth()->id())->where('reaction', 'care')->isNotEmpty();
    
            return $post;
        });
    
        // Handle AJAX requests for infinite scroll
        if ($request->ajax()) {
            $view = view('partials.posts', compact('posts'))->render();
            return response()->json([
                'view' => $view,
                'nextPageUrl' => $posts->nextPageUrl(),
            ]);
        }
    
        // Return the user profile view
        return view('pages.userProfile', compact('posts', 'user'));
    }
    
    

    /**
     * Store a newly created post in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Custom error messages for validation
        $messages = [
            'post_caption.required' => 'Please provide a caption for your post.',
            'post_caption.string' => 'The caption must be a valid string.',
            'posts_file_path.*.mimes' => 'Each file must be one of the following types: jpeg, png, jpg, gif, svg, heif, heic.',
            'posts_file_path.*.max' => 'Each file size cannot exceed 12MB.',
            'post_link.url' => 'The link must be a valid URL.',
        ];
    
        // Validate the input with custom error messages
        $validated = $request->validate([
            'post_caption' => 'required|string',
            'posts_file_path.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,heif,heic|max:12288', // Validate each file
            'post_link' => 'nullable|url',
        ], $messages);
    
        // Store the post
        $post = new Post();
        $post->post_caption = $validated['post_caption'];
        $post->post_status = 'posted'; // Default status
        $post->post_likes = 0;
        $post->post_comments = json_encode([]); // Empty comments by default
        $post->post_user_id = Auth::id(); // Set the user ID of the authenticated user
    
        // Handle file uploads
        $filePaths = [];
        if ($request->hasFile('posts_file_path')) {
            foreach ($request->file('posts_file_path') as $file) {
                // Generate a unique file name
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
    
                // Save the file to the public/images/posts directory
                $file->move(public_path('images/posts'), $fileName);
    
                // Add the file path to the array
                $filePaths[] = 'images/posts/' . $fileName;
            }
        }
    
        // Save the file paths as JSON in the database
        $post->post_file_path = $filePaths;
    
        // Save the post link if provided
        if (!empty($validated['post_link'])) {
            $post->post_link = $validated['post_link'];
        }
    
        $post->save();
    
        // Redirect back with success message
        return redirect()->route('posts.index')->with('success', 'Post created successfully!');
    }    
      

    public function deletePost($postId)
    {
        // Retrieve the post by its ID
        $post = Post::findOrFail($postId);
        
        // Check if the authenticated user is the owner of the post
        if ($post->post_user_id !== Auth::id()) {
            // If the user is not the owner, return an error
            return redirect()->route('posts.index')->with('error', 'You are not authorized to delete this post.');
        }

        // If the user is the owner, delete the post
        $post->delete();

        // Optionally, delete any associated likes or comments
        PostLike::where('post_id', $postId)->delete();  // Delete all likes related to the post
        PostComment::where('post_id', $postId)->delete();  // Delete all comments related to the post

        // Redirect with success message
        return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    }

    
    public function reactToPost(Request $request, $postId)
    {
        $userId = Auth::id();
        $post = Post::findOrFail($postId);
    
        $reaction = $request->input('reaction'); // The reaction type (e.g., like, heart, care)
    
        // Check if the user already reacted to this post
        $existingReaction = PostLike::where('user_id', $userId)
                                    ->where('post_id', $postId)
                                    ->first();
    
        if ($existingReaction) {
            if ($existingReaction->reaction === $reaction) {
                // If the same reaction is selected, remove it (unreact)
                $existingReaction->delete();
                $message = "You removed your reaction.";
                $reactedByUser = false; // User has unreacted to the post
            } else {
                // If a different reaction is selected, update the existing reaction
                $existingReaction->reaction = $reaction;
                $existingReaction->save();
                $reactedByUser = true; // User has reacted to the post
                $message = "You updated your reaction to {$reaction}.";
            }
        } else {
            // Add a new reaction
            PostLike::create([
                'user_id' => $userId,
                'post_id' => $postId,
                'reaction' => $reaction,
            ]);
            $message = "You reacted with {$reaction}.";
            $reactedByUser = true; // User has reacted to the post
        }
    
        // Recalculate reaction counts
        $reactionCounts = [
            'like' => $post->likes()->where('reaction', 'like')->count(),
            'heart' => $post->likes()->where('reaction', 'heart')->count(),
            'care' => $post->likes()->where('reaction', 'care')->count(),
        ];
        $reactionCounts['all'] = $reactionCounts['like'] + $reactionCounts['heart'] + $reactionCounts['care'];
    
        return response()->json([
            'message' => $message,
            'reactionCounts' => $reactionCounts,
            'userReaction' => $reaction, // The user's current reaction
            'reactedByUser' => $reactedByUser,
        ]);
    }
    
    
    
    
    
    public function storeComment(Request $request, $postId)
    {
        $validated = $request->validate([
            'comment_text' => 'required|string|max:5000',
        ]);
    
        $comment = PostComment::create([
            'post_id' => $postId,
            'user_id' => Auth::id(),
            'comment_text' => $validated['comment_text'],
        ]);
    
        return response()->json([
            'success' => true,
            'post_id' => $postId, // Send the postId for reference
            'comment' => [
                'id' => $comment->id, // Include the comment ID
                'user_profile_picture' => $comment->user->user_profile_picture
                    ? base64_encode($comment->user->user_profile_picture)
                    : null,
                'user_fname' => $comment->user->user_fname,
                'user_lname' => $comment->user->user_lname,
                'user_initial' => strtoupper(substr($comment->user->user_fname, 0, 1)),
                'comment_text' => $comment->comment_text,
                'user_id' => Auth::id(),
            ],
        ]);
    }
    
    public function editPost($postId)
    {
        $post = Post::findOrFail($postId);

        return view('pages.editPost', compact('post'));
    }

    /**
     * Handle the update of the post.
     */
    public function updatePost(Request $request, $postId)
    {
        $post = Post::findOrFail($postId);
        
        // Custom error messages for validation
        $messages = [
            'post_caption.required' => 'Please provide a caption for your post.',
            'post_caption.string' => 'The caption must be a valid string.',
            'posts_file_path.*.mimes' => 'Each file must be one of the following types: jpeg, png, jpg, gif, svg, heif, heic.',
            'posts_file_path.*.max' => 'Each file size cannot exceed 12MB.',
            'post_link.url' => 'The link must be a valid URL.',
        ];
    
        // Validate the input with custom error messages
        $validatedData = $request->validate([
            'post_caption' => 'required|string',
            'posts_file_path.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,heif,heic|max:12288', // Validate each file
            'post_link' => 'nullable|url',
        ], $messages);
    
        // Prepare data for update
        $updateData = [
            'post_caption' => $validatedData['post_caption'],
        ];
    
        // Only add 'post_link' if it's not null
        if (!is_null($validatedData['post_link'])) {
            $updateData['post_link'] = $validatedData['post_link'];
        }
    
        // Update the post with validated data
        $post->update($updateData);
    
        // Handle file uploads
        $filePaths = [];

        if ($request->hasFile('posts_file_path')) {
            $filePaths = [];
            foreach ($request->file('posts_file_path') as $file) {
                // Generate a unique file name for each file
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                // Save the file to the public/images/posts directory
                $file->move(public_path('images/posts'), $fileName);

                // Add the file path to the array
                $filePaths[] = 'images/posts/' . $fileName;
            }

            // Save the file paths as JSON in the database
            $post->post_file_path = json_encode($filePaths);
        }

        // Save the file paths as JSON in the database
        if (!empty($filePaths)) {
            $post->post_file_path = $filePaths;
        }
    
        $post->save();
    
        // Redirect back with success message
        return redirect()->route('post.edit', ['postId' => $post->id])->with('success', 'Post updated successfully');
    }
     
    

    public function deleteComment(Request $request, $commentId)
    {
        // Find the comment by ID
        $comment = PostComment::findOrFail($commentId);

        // Ensure only the comment's owner can delete it or check for user permissions
        if ($comment->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to delete this comment.',
            ], 403);
        }

        // Delete the comment
        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully.',
        ]);
    }


    public function storeReply(Request $request, $commentId)
    {
        // Validate input
        $validated = $request->validate([
            'reply_text' => 'required|string|max:5000',
        ]);

        // Find the comment that is being replied to
        $comment = PostComment::findOrFail($commentId);

        // Create a reply to the comment
        $reply = PostCommentReply::create([
            'comment_id' => $commentId,
            'user_id' => Auth::id(),
            'reply_text' => $validated['reply_text'],
        ]);

        return response()->json([
            'success' => true,
            'reply' => [
                'user_profile_picture' => $reply->user->user_profile_picture 
                    ? base64_encode($reply->user->user_profile_picture)
                    : null,
                'user_fname' => $reply->user->user_fname,
                'user_lname' => $reply->user->user_lname,
                'user_initial' => strtoupper(substr($reply->user->user_fname, 0, 1)),
                'reply_text' => $reply->reply_text,
                'created_at' => $reply->created_at,
            ],
        ]);
    }

    
    public function getLikers($postId, $reactionType = null)
    {
        $post = Post::findOrFail($postId);
        
        // Get the list of users who reacted to this post, including the reaction type
        $likersQuery = $post->likes()->with('user')->get();
    
        // If a reaction type is provided, filter the reactions
        if ($reactionType) {
            $likersQuery = $likersQuery->filter(function($like) use ($reactionType) {
                return $like->reaction === $reactionType;
            });
        }
    
        // Map the data to include user information and reaction type
        $likers = $likersQuery->map(function($like) {
            return [
                'user_id' => $like->user->id,
                'user_fname' => $like->user->user_fname,
                'user_lname' => $like->user->user_lname,
                'user_profile_picture' => $like->user->user_profile_picture ? base64_encode($like->user->user_profile_picture) : null,
                'user_initial' => strtoupper(substr($like->user->user_fname, 0, 1)), // Initials for profile picture
                'reaction' => $like->reaction, // Include the reaction type
            ];
        });
    
        return response()->json([
            'likers' => $likers,
        ]);
    }    
}
