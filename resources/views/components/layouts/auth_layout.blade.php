<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-R-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Personal Blog System' }}</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>✍️</text></svg>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="{{ asset('css/auth_layout.css') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https_::fonts.gstatic.com" crossorigin>
    <link href="https_://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">

<div class="container-fluid auth-container-wrapper flex-grow-1">
    <div class="row justify-content-center w-100" style="max-width: 1200px;">
        <div class="col-12">

            <div class="card auth-card">
                <div class="row g-0">

                    <div class="col-md-6 auth-image-side">
                        <img src="{{ asset('images/undraw_blogging_38kl.svg') }}" alt="Blog Illustration">
                    </div>

                    <div class="col-md-6 auth-form-side">

                        {{ $slot }}

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script src="{{ asset('js/auth_layout.js') }}"></script>
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