@foreach ($blogs as $blog)
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
@endforeach
