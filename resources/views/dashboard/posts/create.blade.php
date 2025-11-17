<x-layouts.dashboard_layout>

    <x-slot name="title">
        Create New Post
    </x-slot>

    <h2 class="h4 mb-4">Create New Post</h2>

    <div class="card recent-posts-card">
        <div class="card-body">
            
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" id="postForm">
                @csrf
                
                <div class="mb-3">
                    <label for="title" class="form-label">Post Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" 
                           placeholder="e.g., My First Blog Post" required>
                    @error('title')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="content" class="form-label">Content</label>
                    <textarea class="form-control tinymce-editor" id="content" name="content" rows="10" 
                              placeholder="Write your amazing post here...">{{ old('content') }}</textarea>
                    @error('content')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="featured_image" class="form-label">Featured Image</label>
                    
                    <div class="file-drop-area">
                        <span class="file-drop-icon">
                            <i class="bi bi-cloud-arrow-up-fill"></i>
                        </span>
                        <p class="file-drop-message">
                            Drop your image here, or <span class="click-to-browse">click to browse</span>
                        </p>
                        <p class="file-drop-subtext">
                            PNG, JPG or GIF (Max 2MB)
                        </p>
                        <input type="file" class="file-drop-input" id="featured_image" name="featured_image">
                        <div class="file-drop-preview"></div>
                    </div>
                    
                    @error('featured_image')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Categories</label>
                    <div class="category-checkbox-list">
                        @forelse ($categories as $category)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="categories[]" 
                                    value="{{ $category->id }}" id="category-{{ $category->id }}">
                                <label class="form-check-label" for="category-{{ $category->id }}">
                                    {{ $category->name }}
                                </label>
                            </div>
                        @empty
                            <p class="text-muted small mb-0">No categories created yet. 
                                <a href="{{ route('dashboard.categories.index') }}">Manage Categories</a>
                            </p>
                        @endforelse
                    </div>
                </div>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="is_published" name="is_published" value="1">
                    <label class="form-check-label" for="is_published">Publish this post</label>
                </div>
                
                <hr>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Post</button>
                </div>

            </form>

        </div>
    </div>

</x-layouts.dashboard_layout>