@extends('layouts.public_layout')

@section('title', $post->title)

@section('content')

<div class="post-content-wrapper"> 
    
    <div class="post-header-section text-center">
        
        <h1 class="display-4 fw-bold mb-3">{{ $post->title }}</h1>

        <div class="text-muted mb-4 post-metadata">
            Posted by <strong>{{ $post->user?->name ?? 'Unknown Author' }}</strong>
            on {{ $post->created_at->format('F d, Y') }}
        </div>
    </div>

    @if ($post->featured_image)
        <img src="{{ asset('storage/' . $post->featured_image) }}" 
             class="img-fluid rounded mb-4 post-hero-image" 
             alt="{{ $post->title }}">
    @endif

    <div class="card recent-posts-card post-content-card">

        <div class="post-full-content">
            {!! $post->content !!}
        </div>

        <hr class="my-4">

        <h3 class="mb-3">Comments ({{ $post->comments->count() }})</h3>

        <div class="comment-form-wrapper mb-4">
            <form action="{{ route('comments.store', $post->id) }}" method="POST">
                @csrf

                @guest
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="guest_name" class="form-label">Name*</label>
                        <input type="text" class="form-control" id="guest_name" name="guest_name" value="{{ old('guest_name') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="guest_email" class="form-label">Email*</label>
                        <input type="email" class="form-control" id="guest_email" name="guest_email" value="{{ old('guest_email') }}" required>
                    </div>
                </div>
                @endguest

                @auth
                <p class="commenting-as-text">Commenting as: <strong>{{ Auth::user()->name }}</strong></p>
                @endauth

                <div class="mb-3">
                    <label for="content" class="form-label">Your Comment*</label>
                    <textarea class="form-control" id="content" name="content" rows="4" required>{{ old('content') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Post Comment</button>
            </form>
        </div>

        <div class="comment-list">
            @forelse ($post->comments as $comment)
                <div class="comment-item d-flex mb-4">
                    <div class="flex-shrink-0 me-3">
                        <img src="{{ $comment->user?->avatar_path ? asset('storage/' . $comment->user->avatar_path) : asset('images/undraw_developer-avatar_f6ac.svg') }}" 
                             class="rounded-circle comment-avatar">
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="mt-0 mb-1 comment-author">
                            {{ $comment->user ? $comment->user->name : $comment->guest_name }}
                        </h5>
                        <p class="comment-timestamp">
                            {{ $comment->created_at->diffForHumans() }} </p>
                        <p class="comment-content">
                            {{ $comment->content }}
                        </p>
                    </div>
                </div>
            @empty
                <p class="text-muted">No comments yet. Be the first to comment!</p>
            @endforelse
        </div>

        <hr class="my-4">

        <a href="{{ route('home') }}" class="btn btn-outline-secondary" style="max-width: 200px;">
            <i class="bi bi-arrow-left"></i> Back to All Posts
        </a>
    </div>
</div>

@endsection