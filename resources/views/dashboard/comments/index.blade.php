<x-layouts.dashboard_layout>

    <x-slot name="title">
        Manage Comments
    </x-slot>

    <h2 class="h4 mb-4">Manage All Comments</h2>

    <div class="card recent-posts-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="commentsTable">
                    <thead>
                        <tr>
                            <th style="width: 30%;">Comment</th>
                            <th style="width: 20%;">Author</th>
                            <th style="width: 30%;">In Response To</th>
                            <th style="width: 10%;">Status</th>
                            <th style="width: 10%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($comments as $comment)
                            <tr>
                                <td class="truncate-cell" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $comment->content }}">
                                    {{ $comment->content }}
                                </td>

                                <td>
                                    @if ($comment->user)
                                        <strong class="text-dark">{{ $comment->user->name }}</strong>
                                    @else
                                        <strong>{{ $comment->guest_name }}</strong>
                                        <br><small class="text-muted">{{ $comment->guest_email }}</small>
                                    @endif
                                </td>

                                <td>
                                    <a href="{{ route('posts.show', $comment->post->id) }}" target="_blank" class="text-decoration-none">
                                        {{ $comment->post->title }}
                                    </a>
                                </td>

                                <td>
                                    @if ($comment->is_verified)
                                        <span class="badge-status badge-published">Verified</span>
                                    @else
                                        <span class="badge-status badge-draft">Pending</span>
                                    @endif
                                </td>

                                <td>
                                    @if (!$comment->is_verified)
                                        <form action="{{ route('dashboard.comments.approve', $comment->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-success" 
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Approve">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('dashboard.comments.destroy', $comment->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this comment?');">
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
    </div>

</x-layouts.dashboard_layout>