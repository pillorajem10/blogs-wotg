<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostLike;
use App\Models\PostComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

use Carbon\Carbon;
use App\Models\Blog;

class PostController extends Controller
{
    /*
    public function __construct()
    {
        $this->middleware('auth'); // Ensure the user is authenticated
    }
    */
    /**
     * Display a listing of the posts.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $today = Carbon::now('Asia/Manila');
        
        // Retrieve all posts from the database, eager load the likes relationship, and order by created_at
        $posts = Post::with('likes.user') // Eager load the likes and the user who liked it
                     ->orderBy('created_at', 'desc') // Sort posts by the most recent ones
                     ->get(); // Get all posts
    
        $blogs = Blog::where('blog_release_date_and_time', '<=', $today)
                     ->where('blog_approved', true)
                     ->orderBy('blog_release_date_and_time', 'desc') // Order by release date descending
                     ->limit(8) // Limit the results to 8
                     ->get();
        
    
        // Add 'likedByUser' attribute to each post to check if the authenticated user has liked it
        $posts->map(function ($post) {
            $post->likedByUser = $post->likes()->where('user_id', auth()->id())->exists();
            return $post;
        });
        
        // Retrieve the authenticated user's details
        $user = auth()->user();
        
        // Pass the posts, user details, and the likers to the Blade view
        return view('pages.posts', compact('posts', 'user', 'blogs'));
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
            'post_image.image' => 'Please upload a valid image file (jpeg, png, jpg, gif, svg).',
            'post_image.mimes' => 'The image must be one of the following types: jpeg, png, jpg, gif, svg, heif, heic.',
            'post_image.max' => 'The image size cannot exceed 12MB.',
        ];
    
        // Validate the input with custom error messages
        $validated = $request->validate([
            'post_caption' => 'required|string',
            'post_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,heif,heic|max:12288', 
        ], $messages);                      
    
        // Store the post
        $post = new Post();
        $post->post_caption = $validated['post_caption'];
        $post->post_status = 'posted'; // Default status
        $post->post_likes = 0;
        $post->post_comments = json_encode([]); // Empty comments by default
        $post->post_user_id = Auth::id(); // Set the user ID of the authenticated user
    
        // Handle the image upload if there is one
        if ($request->hasFile('post_image')) {
            $image = $request->file('post_image');
    
            // Convert the image to binary
            $post->post_image = file_get_contents($image);
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

    
    public function likePost(Request $request, $postId)
    {
        $userId = Auth::id();
        $post = Post::findOrFail($postId);
    
        // Check if the user has already liked the post
        $existingLike = PostLike::where('user_id', $userId)
                                ->where('post_id', $postId)
                                ->first();
    
        if ($existingLike) {
            // If the post is already liked, unlike it
            $existingLike->delete();
            $post->post_likes = $post->post_likes - 1; // Decrease the like count
            $likedByUser = false; // User has unliked the post
            $message = 'You unliked this post.';
        } else {
            // If the post is not liked, like it
            PostLike::create([
                'user_id' => $userId,
                'post_id' => $postId,
            ]);
            $post->post_likes = $post->post_likes + 1; // Increase the like count
            $likedByUser = true; // User has liked the post
            $message = 'You liked this post.';
        }
    
        // Save the updated like count on the Post model
        $post->save();
    
        // Return the updated like count and likedByUser status in JSON format
        return response()->json([
            'message' => $message,
            'likesCount' => $post->post_likes,
            'likedByUser' => $likedByUser,
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
            'comment' => [
                'user_profile_picture' => $comment->user->user_profile_picture 
                    ? base64_encode($comment->user->user_profile_picture)
                    : null,
                'user_fname' => $comment->user->user_fname,
                'user_lname' => $comment->user->user_lname,
                'user_initial' => strtoupper(substr($comment->user->user_fname, 0, 1)),
                'comment_text' => $comment->comment_text,
            ],
        ]);
    } 
    
    public function getLikers($postId)
    {
        $post = Post::findOrFail($postId);
        
        // Get the list of users who liked this post
        $likers = $post->likes()->with('user')->get()->map(function($like) {
            return [
                'user_id' => $like->user->id,
                'user_fname' => $like->user->user_fname,
                'user_lname' => $like->user->user_lname,
                'user_profile_picture' => base64_encode($like->user->user_profile_picture) ?? null,
                'user_initial' => strtoupper(substr($like->user->user_fname, 0, 1)), // Initials for profile picture
            ];
        });

        return response()->json([
            'likers' => $likers,
        ]);
    }
}
