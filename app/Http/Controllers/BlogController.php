<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; 

class BlogController extends Controller
{    
    public function index()
    {
        // Set the timezone to Manila
        $today = Carbon::now('Asia/Manila');
        
        // Log the current date
        Log::info('Fetching blogs on date: ' . $today->toDateTimeString());
        
        // Fetch all approved blogs with a release date less than or equal to today, 
        // ordered in descending order of the release date, and paginate with 5 items per page
        $blogs = Blog::where('blog_release_date_and_time', '<=', $today)
                     ->where('blog_approved', true)
                     ->orderBy('blog_release_date_and_time', 'desc') // Order by release date descending
                     ->paginate(5); // Pagination with 5 blogs per page
        
        return view('pages.blogs', compact('blogs'));
    }
       

    // Show the details of a specific blog
    public function show($id)
    {
        // Fetch the current blog
        $blog = Blog::find($id); // Use find instead of findOrFail to allow custom handling
        
        // Get the current date and time in Manila timezone
        $today = Carbon::now('Asia/Manila');
        
        // Check if the blog exists and is approved
        if (!$blog || !$blog->blog_approved) {
            return redirect()->route('blogs.index')->with('error', 'No blog found');
        }
    
        // Get the previous blog by ID (find the blog with an ID smaller than the current one)
        $prevBlog = Blog::where('blog_approved', true)
                        ->where('blog_release_date_and_time', '<=', $today)
                        ->where('id', '<', $blog->id)  // Get blogs with smaller IDs
                        ->orderBy('id', 'desc')        // Order by ID descending (so the latest previous blog is picked)
                        ->first();
    
        // Get the next blog by ID (find the blog with an ID greater than the current one)
        $nextBlog = Blog::where('blog_approved', true)
                        ->where('blog_release_date_and_time', '<=', $today)
                        ->where('id', '>', $blog->id)  // Get blogs with greater IDs
                        ->orderBy('id', 'asc')         // Order by ID ascending (so the earliest next blog is picked)
                        ->first();
        
        // Return the view with blog, previous, and next blog data
        return view('pages.blogDetails', compact('blog', 'prevBlog', 'nextBlog'));
    }  
    
    public function writeComment(Request $request, $blogId)
    {
        // Ensure the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('auth.login')->with('error', 'You must be logged in to comment.');
        }

        // Validate the comment input
        $validatedData = $request->validate([
            'comment_body' => 'required|string|max:1000',
        ]);

        // Create a new comment
        $comment = new Comment();
        $comment->comment_blogid = $blogId; // The blog being commented on
        $comment->comment_userid = Auth::id(); // The user posting the comment
        $comment->comment_body = $validatedData['comment_body'];

        // Save the comment to the database
        $comment->save();

        // Redirect back to the blog page with a success message
        return redirect()->route('blogs.show', $blogId)->with('success', 'Your comment has been posted.');
    }
}
