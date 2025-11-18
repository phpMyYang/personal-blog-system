<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }} - Personal Blog</title>

    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>✍️</text></svg>">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard_layout.css') }}">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>
</head>
<body class="d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            Personal Blog
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('dashboard') ? 'active' : '' }}" 
                    href="{{ route('dashboard') }}"><i class="bi bi-grid-fill me-2"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ (Route::is('posts.create') || Route::is('posts.edit')) ? 'active' : '' }}" 
                    href="{{ route('posts.create') }}"><i class="bi bi-file-earmark-plus-fill me-2"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('dashboard.categories.index') ? 'active' : '' }}" 
                    href="{{ route('dashboard.categories.index') }}"><i class="bi bi-tags-fill me-2"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('dashboard.comments.index') ? 'active' : '' }}" 
                    href="{{ route('dashboard.comments.index') }}"><i class="bi bi-chat-dots me-2"></i></a>
                </li>
            </ul>
            
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ Auth::user()->avatar_path 
                            ? asset('storage/' . Auth::user()->avatar_path) 
                            : asset('images/undraw_developer-avatar_f6ac.svg') }}" 
                            alt="{{ Auth::user()->name }}"
                            style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; border: 3px solid var(--color-background); box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">

                        <div class="user-dropdown-header">
                            <img src="{{ Auth::user()->avatar_path 
                                ? asset('storage/' . Auth::user()->avatar_path) 
                                : asset('images/undraw_developer-avatar_f6ac.svg') }}" 
                                alt="{{ Auth::user()->name }}"
                                class="avatar-sm rounded-circle">
                            <div>
                                <strong>{{ Auth::user()->name }}</strong><br>
                                <span class="email-text">{{ Auth::user()->email }}</span>
                            </div>
                        </div>

                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="bi bi-person-circle me-2"></i> My Profile
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>

                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="bi bi-box-arrow-left me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="container py-4 flex-grow-1">
    {{ $slot }}
</main>

<footer class="footer-dashboard py-4">
    <div class="container text-center small text-muted">
        Personal Blog System &copy; {{ date('Y') }}. All rights reserved.
    </div>
</footer>

@if (session('success'))
    <x-toast type="success" message="{{ session('success') }}" />
@endif
@if (session('error'))
    <x-toast type="error" message="{{ session('error') }}" />
@endif

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.tiny.cloud/1/mbnysu8x84ywme1e9dec6zoc6jbna703csbf677wczxgen7y/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script src="{{ asset('js/dashboard_layout.js') }}"></script>
<script src="{{ asset('js/toast_handler.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Tumawag sa external function para sa lahat ng dynamic IDs
        @if (session('success'))
            initializeToast('liveToast-success');
        @endif
        @if (session('error'))
            initializeToast('liveToast-error');
        @endif
    });
</script>

@stack('scripts')

</body>
</html>