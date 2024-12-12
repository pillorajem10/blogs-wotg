@extends('layouts.layout')

@section('title', 'Edit Post')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/editPost.css?v=7.1') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
@endsection

@section('content')
    <div class="container">
        <h3>Edit Post</h3>
        
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('post.update', ['postId' => $post->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Show existing image if it exists -->
            @if ($post->post_image)
                <div class="form-group">
                    <label>Current Image</label>
                    <div class="image-preview">
                        <img src="data:image/jpeg;base64,{{ base64_encode($post->post_image) }}" alt="Post Image" class="img-thumbnail">
                    </div>
                </div>
            @endif

            <div class="form-group">
                <label for="post_image">Upload New Image (optional)</label>
                <input type="file" name="post_image" class="form-control">
                @error('post_image')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="post_caption">Caption</label>
                <textarea name="post_caption" class="form-control" rows="3">{{ old('post_caption', $post->post_caption) }}</textarea>
                @error('post_caption')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="post_link">Shared Link</label>
                <input type="text" name="post_link" class="form-control" value="{{ old('post_link', $post->post_link) }}">
                @error('post_link')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="actions">
                <button type="submit" class="btn-custom-edit-post">Save Changes</button>
                <a href="{{ route('posts.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection
