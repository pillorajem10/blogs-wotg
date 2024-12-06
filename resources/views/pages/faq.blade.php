<!-- resources/views/pages/faq.blade.php -->
@extends('layouts.layout')

@section('title', 'FAQ')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/faq.css?v=3.2') }}">
@endsection

@section('content')
    <div class="faq-container">
{{--        <h1 class="faq-title">Frequently Asked Questions</h1>
        
        <div class="faq-item">
            <h3 class="faq-question">What is Word On The Go?</h3>
            <p class="faq-answer">Word On The Go is a church community focused on spreading faith, hope, and love through digital content and real-life connections.</p>
        </div>
        
        <div class="faq-item">
            <h3 class="faq-question">How can I get involved in the church?</h3>
            <p class="faq-answer">You can join one of our D-Groups, volunteer for ministry, or participate in our events and services.</p>
        </div>
        
        <div class="faq-item">
            <h3 class="faq-question">Is there an online service available?</h3>
            <p class="faq-answer">Yes, we offer online services through our website and social media platforms. You can watch messages anytime!</p>
        </div>
--}}
        
        <!-- Add more FAQ items here -->
    </div>
@endsection
