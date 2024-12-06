@extends('layouts.layout')

@section('title', 'Blogs')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/blogs.css?v=3.1') }}">
@endsection

@section('content')
    <!-- Loading Overlay -->
    <div id="loading-overlay" class="loading-overlay">
        <div class="loading-spinner"></div>
    </div>

    <div>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="header-container">
            <div>
                Romans 1:16<br>
                “For I am not ashamed of the gospel, because it is the power of God that brings salvation to everyone who believes…”
            </div>
        </div>
        
        {{--<div class="text-center mb-4 mt-4">
            <a href="{{ route('subscribers.signup') }}" class="custom-signup-btn">Subscribe To Be Notified</a>
        </div>--}}

        <div class="card-container">
            @forelse ($blogs as $blog)
                <div class="blog-card">
                    <div class="blog-card-header">
                        <h3 class="blog-title">{{ $blog->blog_title }}</h3>
                    </div>
                    <div class="blog-card-body">
                        @if($blog->blog_thumbnail)
                            <img src="data:image/jpeg;base64,{{ base64_encode($blog->blog_thumbnail) }}" alt="{{ $blog->blog_title }}" class="blog-thumbnail">
                        @endif
                        <p class="blog-body">
                            {{ Str::limit(html_entity_decode(strip_tags($blog->blog_body)), 200) }}
                        </p>
                    </div>
                    <div class="blog-card-footer">
                        <a href="{{ route('blogs.show', $blog->id) }}" class="btn-view">See More</a>
                    </div>
                </div>
            @empty
                <div class="text-center">No blogs found.</div>
            @endforelse
        </div>

        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                {{ $blogs->appends(['search' => session('search'), 'page' => session('page')])->links('vendor.pagination.bootstrap-4') }}
            </ul>
        </nav> 
    </div>

    <!-- Include JS file -->
    <script src="{{ asset('js/blogs.js?v=3.1') }}"></script>
@endsection
