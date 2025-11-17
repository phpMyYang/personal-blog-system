<x-layouts.dashboard_layout>

    <x-slot name="title">
        Dashboard
    </x-slot>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4">Welcome, {{ Auth::user()->name }}!</h2>
        <a href="{{ route('posts.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle-fill me-1"></i>
            Create New Post
        </a>
    </div>

    <h3 class="h5 mb-3">Your Stats</h3>
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card stat-card text-center">
                <div class="card-body">
                    <div class="stat-icon stat-icon-total">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <h5 class="stat-number">{{ $totalPosts }}</h5>
                    <p class="stat-label">Total Posts Created</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card stat-card text-center">
                <div class="card-body">
                    <div class="stat-icon stat-icon-published">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <h5 class="stat-number">{{ $publishedPosts }}</h5>
                    <p class="stat-label">Published Posts</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card stat-card text-center">
                <div class="card-body">
                    <div class="stat-icon stat-icon-drafts">
                        <i class="bi bi-pencil-square"></i>
                    </div>
                    <h5 class="stat-number">{{ $drafts }}</h5>
                    <p class="stat-label">Drafts</p>
                </div>
            </div>
        </div>
    </div>

    <hr class="my-4">

    <h3 class="h5 mb-3">Recent Posts</h3>
    <div class="card recent-posts-card">

        <div class="card-body"> 

            <table class="table table-hover mb-0" id="postsTable">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($posts as $post)
                        <tr>
                           <td class="truncate-cell" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $post->title }}">
                                <strong class="text-dark">{{ $post->title }}</strong>
                            </td>
                            <td>
                                @if ($post->is_published)
                                    <span class="badge-status badge-published">Published</span>
                                @else
                                    <span class="badge-status badge-draft">Draft</span>
                                @endif
                            </td>
                            <td>
                                {{ $post->created_at->format('M d, Y') }}
                            </td>
                            <td>
                                <a href="{{ route('posts.show', $post->id) }}" class="btn btn-sm btn-outline-info" 
                                data-bs-toggle="tooltip" data-bs-placement="top" title="View Post">
                                    <i class="bi bi-eye-fill"></i>
                                </a>

                                <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-sm btn-outline-secondary" 
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>

                                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this post?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
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

</x-layouts.dashboard_layout>