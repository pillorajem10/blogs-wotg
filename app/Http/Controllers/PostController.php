<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

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
    public function index()
    {
        // Retrieve all posts from the database
        $posts = Post::with('likes') // Eager load the likes relationship
                     ->get(); // Get all posts
        
        // Shuffle the posts to randomize the order
        $posts = $posts->shuffle(); // Shuffle the collection of posts
        
        // Add 'likedByUser' attribute to each post to check if the authenticated user has liked it
        $posts->map(function ($post) {
            $post->likedByUser = $post->likes()->where('user_id', auth()->id())->exists();
            return $post;
        });
    
        // Retrieve the authenticated user's details
        $user = auth()->user();
    
        // Pass the posts and user details to the Blade view
        return view('pages.posts', compact('posts', 'user'));
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
            'post_image.mimes' => 'The image must be one of the following types: jpeg, png, jpg, gif, svg.',
            'post_image.max' => 'The image size cannot exceed 8MB.',
        ];
    
        // Validate the input with custom error messages
        $validated = $request->validate([
            'post_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:8048',
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
}
