<?php

namespace App\Http\Controllers;

use App\Models\Post;
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
        $posts = Post::all(); // Retrieve all posts
    
        // Shuffle the posts to randomize the order
        $posts = $posts->shuffle(); // Shuffle the collection of posts
    
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
            'post_caption.max' => 'The caption cannot be longer than 255 characters.',
            'post_image.image' => 'Please upload a valid image file (jpeg, png, jpg, gif, svg).',
            'post_image.mimes' => 'The image must be one of the following types: jpeg, png, jpg, gif, svg.',
            'post_image.max' => 'The image size cannot exceed 8MB.',
        ];
    
        // Validate the input with custom error messages
        $validated = $request->validate([
            'post_caption' => 'required|string|max:255',
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
}
