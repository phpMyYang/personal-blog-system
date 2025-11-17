<x-layouts.dashboard_layout>

    <x-slot name="title">
        Edit Category
    </x-slot>

    <h2 class="h4 mb-4">Edit Category</h2>

    <div class="card recent-posts-card">
        <div class="card-body">
            <form action="{{ route('dashboard.categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" 
                           value="{{ old('name', $category->name) }}" required>
                </div>

                <div class="mb-3">
                    <label for="slug" class="form-label">Slug</label>
                    <input type="text" class="form-control" id="slug" name="slug" 
                           value="{{ old('slug', $category->slug) }}" required>
                    <p class="text-muted small mt-1">Ang "slug" ay ang URL-friendly version ng pangalan.</p>
                </div>

                <a href="{{ route('dashboard.categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Category</button>
            </form>
        </div>
    </div>

</x-layouts.dashboard_layout>