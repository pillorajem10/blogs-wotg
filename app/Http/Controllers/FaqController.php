<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Ensure the user is authenticated
    }
    
    // Method to display the FAQ page
    public function index()
    {
        return view('pages.faq');  // Ensure this matches your file structure
    }
}

