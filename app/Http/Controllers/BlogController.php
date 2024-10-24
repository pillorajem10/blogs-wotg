<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
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
    
        // Fetch all approved blogs with a release date less than or equal to today
        $blogs = Blog::where('blog_release_date_and_time', '<=', $today)
                     ->where('blog_approved', true) // Add this line
                     ->get();
    
        return view('pages.blogs', compact('blogs'));
    }    

    // Show the details of a specific blog
    public function show($id)
    {
        $blog = Blog::find($id); // Use find instead of findOrFail to allow custom handling
    
        if (!$blog || !$blog->blog_approved) { // Check if blog exists and is approved
            return redirect()->route('blogs.index')->with('error', 'No blog found');
        }
    
        return view('pages.blogDetails', compact('blog'));
    }
    

    // Show the form for creating a new blog
    public function create()
    {
        return view('pages.addBlog');
    }
}
