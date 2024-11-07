@extends('layouts.layout')

@section('title', 'Blogs')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/blogs.css?v=1.2') }}">
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
            Trust in the Lord with all your heart; lean not on your own understanding. In all your ways, acknowledge Him, and He will direct your path
        </div>        

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

    </div>

    <!-- Include JS file -->
    <script src="{{ asset('js/blogs.js?v=1.2') }}"></script>
@endsection
