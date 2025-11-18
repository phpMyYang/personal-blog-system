<x-layouts.dashboard_layout>

    <x-slot name="title">
        Manage Categories
    </x-slot>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0">Manage Categories</h2>
        
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="bi bi-plus-circle-fill me-1"></i>
            Add New Category
        </button>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card recent-posts-card">
                <div class="card-body">
                    {{-- <div class="table-responsive"> --}}
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
                                            {{ $category->posts_count ?? $category->posts->count() }}
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-secondary edit-btn" 
                                               data-bs-toggle="tooltip" title="Edit"
                                               data-id="{{ $category->id }}"
                                               data-name="{{ $category->name }}"
                                               data-slug="{{ $category->slug }}"
                                               data-update-url="{{ route('dashboard.categories.update', $category->id) }}">
                                                <i class="bi bi-pencil-fill"></i>
                                            </button>

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
                    {{-- </div> --}}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('dashboard.categories.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="add_name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="add_name" name="name" 
                                   placeholder="e.g., 'Technology'" required>
                            <p class="text-muted small mt-1">Ang slug ay awtomatikong gagawin mula sa pangalan.</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editCategoryForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="edit_slug" class="form-label">Slug</label>
                            <input type="text" class="form-control" id="edit_slug" name="slug" required>
                            <p class="text-muted small mt-1">Ang "slug" ay ang URL-friendly version ng pangalan.</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-layouts.dashboard_layout>