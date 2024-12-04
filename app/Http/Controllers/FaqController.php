<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FaqController extends Controller
{
    // Method to display the FAQ page
    public function index()
    {
        return view('pages.faq');  // Ensure this matches your file structure
    }
}

