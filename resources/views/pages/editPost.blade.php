@extends('layouts.layout')

@section('title', 'Edit Post')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/editPost.css?v=8.8') }}">
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

            <!-- Show the existing post image if it exists -->
            @if ($post->post_image)
                <div class="form-group">
                    <label>Current Image</label>
                    <div class="post-image">
                        <img src="data:image/jpeg;base64,{{ base64_encode($post->post_image) }}" alt="Post Image" class="img-fluid lazy" loading="lazy">
                    </div>
                    <div class="image-preview-notice">
                        <small>If you want to change the image, upload a new one below.</small>
                    </div>
                </div>
            @endif

            <!-- Show additional uploaded images/videos if they exist -->
            @if (!empty($post->post_file_path) && is_array($post->post_file_path))
                <div class="form-group">
                    <label>Additional Media</label>
                    <div class="post-images">
                        @foreach ($post->post_file_path as $filePath)
                            <div class="post-image">
                                @php
                                    $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                                @endphp
            
                                @if (in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']))
                                    <!-- If it's an image -->
                                    <img src="{{ asset($filePath) }}?v={{ time() }}" alt="Post Image" class="img-fluid lazy" loading="lazy">
                                @elseif (in_array(strtolower($fileExtension), ['mp4', 'webm', 'ogg', 'avi', 'mov', 'mkv']))
                                    <!-- If it's a video -->
                                    <video class="img-fluid lazy" controls loading="lazy">
                                        <source src="{{ asset($filePath) }}?v={{ time() }}" type="video/{{ $fileExtension }}">
                                        Your browser does not support the video tag.
                                    </video>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <div class="image-preview-notice">
                        <small>If you want to change any of these images or videos, upload new ones below.</small>
                    </div>
                </div>
            @endif
        
            <!-- Upload New Images/Videos (optional) -->
            <div class="form-group">
                <label for="posts_file_path" class="upload-icon-container">
                    <i class="fa fa-upload"></i> Upload New Media
                </label>
                <input type="file" name="posts_file_path[]" class="form-control file-input" multiple id="posts_file_path" style="display:none;" onchange="previewMedia()">
                
                @error('posts_file_path')
                    <div class="text-danger">{{ $message }}</div>
                @enderror

                <!-- Media Preview Area -->
                <div id="image-preview-container" class="image-preview-container">
                    <!-- Media previews will appear here -->
                </div>
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

        <script src="{{ asset('js/editPost.js?v=8.8') }}"></script>
    </div>
@endsection
