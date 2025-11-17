@extends('layouts.public_layout')

@section('title', $search ? "Search Results" : ($currentCategory ? $currentCategory->name : "Featured Posts"))

@section('content')

    <div class="row justify-content-center mb-4">
        <div class="col-md-8">
            <form action="{{ route('home') }}" method="GET">
                @if($currentCategory)
                    <input type="hidden" name="category" value="{{ $currentCategory->slug }}">
                @endif

                <div class="input-group input-group-lg">
                    <input type="text" class="form-control" name="search" placeholder="Search for posts..." value="{{ $search ?? '' }}">
                    <button class="btn btn-primary" type="submit" id="button-search">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="category-filter-nav mb-5">
        <ul class="nav nav-pills justify-content-center">
            <li class="nav-item">
                <a class="nav-link {{ !$currentCategory ? 'active' : '' }}" 
                   href="{{ route('home', ['search' => $search]) }}">All Posts</a>
            </li>
            @foreach ($categories as $category)
            <li class="nav-item">
                <a class="nav-link {{ $currentCategory && $currentCategory->id == $category->id ? 'active' : '' }}" 
                   href="{{ route('home', ['category' => $category->slug, 'search' => $search]) }}">
                   {{ $category->name }}
                </a>
            </li>
            @endforeach
        </ul>
    </div>


    @if ($search || $currentCategory)

        <h2 class="mb-4">
            @if($search && $currentCategory)
                Search results for "{{ $search }}" in {{ $currentCategory->name }}
            @elseif($search)
                Search results for "{{ $search }}"
            @elseif($currentCategory)
                Showing posts in {{ $currentCategory->name }}
            @endif
        </h2>

    @forelse ($posts as $post)
        <div class="card search-result-item mb-4" style="--animation-delay: {{ $loop->iteration * 0.1 }}s;">
            <div class="row g-0">
                
                @if ($post->featured_image)
                    <div class="col-lg-4 col-md-5">
                        <a href="{{ route('posts.show', $post->id) }}" class="search-result-image-link">
                            <img src="{{ asset('storage/' . $post->featured_image) }}" 
                                 class="search-result-image" 
                                 alt="{{ $post->title }}">
                        </a>
                    </div>
                @endif

                <div class="{{ $post->featured_image ? 'col-lg-8 col-md-7' : 'col-12' }}">
                    <div class="card-body">
                        
                        <h4 class="card-title search-result-title">
                            <a href="{{ route('posts.show', $post->id) }}">{{ $post->title }}</a>
                        </h4>
                        
                        <p class="card-text small text-muted mb-2">
                            By <strong>{{ $post->user?->name ?? 'Unknown Author' }}</strong>
                            <i class="bi bi-dot mx-1"></i> {{ $post->created_at->format('M d, Y') }}
                        </p>
                        
                        <p class="card-text d-none d-md-block">
                            {{ Str::words(strip_tags($post->content), 30) }}
                        </p>
                        
                        <a href="{{ route('posts.show', $post->id) }}" class="btn btn-primary btn-sm mt-2">
                            Read More
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-warning text-center">
                No posts found.
            </div>
        </div>
    @endforelse

    @else

        <div class="mb-5 position-relative">
            <div class="carousel-indicators custom-indicators">
                @foreach ($posts as $post)
                    <button type="button" data-bs-target="#featuredCarousel" data-bs-slide-to="{{ $loop->index }}" 
                        class="{{ $loop->first ? 'active' : '' }}" aria-current="{{ $loop->first ? 'true' : 'false' }}" 
                        aria-label="Slide {{ $loop->iteration }}"></button>
                @endforeach
            </div>

            <div id="featuredCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="7000" data-bs-pause="hover" data-bs-wrap="true">
                <div class="carousel-inner">
                    @forelse ($posts as $post)
                        <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                            <div class="row g-0 carousel-content-row"> 
                                <div class="col-md-5 d-flex justify-content-center align-items-center">
                                    @if ($post->featured_image)
                                        <img src="{{ asset('storage/' . $post->featured_image) }}" class="d-block w-100 rounded" alt="{{ $post->title }}">
                                    @else
                                        <div class="no-image-placeholder">No Image</div>
                                    @endif
                                </div>
                                <div class="col-md-7 carousel-caption">
                                    <span class="badge bg-success mb-2">Featured</span>
                                    <h2 class="fw-bold mb-3">{{ $post->title }}</h2>
                                    <p class="lead text-muted">
                                        {{ Str::words(strip_tags($post->content), 35) }} </p>
                                    <p class="small text-muted mb-4">
                                        By <strong>{{ $post->user?->name ?? 'Unknown Author' }}</strong> on {{ $post->created_at->format('M d, Y') }}
                                    </p>
                                    <a href="{{ route('posts.show', $post->id) }}" class="btn btn-lg btn-primary">
                                        Read More <i class="bi bi-arrow-right-short"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="carousel-item active">
                            <div class="alert alert-info">No published posts found yet.</div>
                        </div>
                    @endforelse
                </div>
            </div>

        </div> 
    @endif 
@endsection