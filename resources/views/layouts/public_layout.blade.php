<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Welcome') - Personal Blog</title>

    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>✍️</text></svg>">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/public_styles.css') }}">
</head>

<body class="d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">Personal Blog</a>

        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('home') }}" data-bs-toggle="tooltip" title="Home"><i class="bi bi-house-door"></i></a>
            </li>

            @guest
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}" data-bs-toggle="tooltip" title="Login"><i class="bi bi-box-arrow-in-right"></i></a>
                </li>
            @endguest

            @auth
                <li class="nav-item dropdown">
                    <a class="nav-link p-0" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ Auth::user()->avatar_path 
                            ? asset('storage/' . Auth::user()->avatar_path) 
                            : asset('images/undraw_developer-avatar_f6ac.svg') }}" 
                            alt="{{ Auth::user()->name }}"
                            style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; border: 3px solid var(--color-background); box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="bi bi-grid-fill me-2"></i> Dashboard</a></li>
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
            @endauth
        </ul>
    </div>
</nav>

<main class="container py-5 flex-grow-1">
    @yield('content')
</main>

<footer class="footer-public py-4">
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/public_scripts.js') }}"></script>
<script src="{{ asset('js/toast_handler.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if (session('success'))
            initializeToast('liveToast-success');
        @endif
        @if (session('error'))
            initializeToast('liveToast-error');
        @endif
    });
</script>

</body>
</html>