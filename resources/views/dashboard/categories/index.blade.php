<x-layouts.dashboard_layout>

    <x-slot name="title">
        Manage Categories
    </x-slot>

    <h2 class="h4 mb-4">Manage Categories</h2>

    <div class="row">
        <div class="col-md-4">
            <div class="card recent-posts-card">
                <div class="card-header bg-white profile-card-header py-3">
                    <h5 class="mb-0">Add New Category</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('dashboard.categories.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   placeholder="e.g., 'Technology'" required>
                            <p class="text-muted small mt-1">The name is how it appears on your site.</p>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Category</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card recent-posts-card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="categoriesTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Total Posts</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $category)
                                    <tr>
                                        <td>
                                            <strong class="text-dark">{{ $category->name }}</strong>
                                        </td>
                                        <td>{{ $category->slug }}</td>
                                        <td>
                                            {{ $category->posts->count() }}
                                        </td>
                                        <td>
                                            <a href="{{ route('dashboard.categories.edit', $category->id) }}" class="btn btn-sm btn-outline-secondary" 
                                               data-bs-toggle="tooltip" title="Edit">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>

                                            <form action="{{ route('dashboard.categories.destroy', $category->id) }}" method="POST" class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this category?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                        data-bs-toggle="tooltip" title="Delete">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.dashboard_layout>