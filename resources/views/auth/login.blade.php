<x-layouts.auth_layout>

    <x-slot name="title">
        Sign In
    </x-slot>

    <div class="auth-logo">
        Personal Blog
    </div>

    <h3 class="mb-2">Sign In to your Account</h3>
    <p class="text-muted mb-4">Welcome back! Please enter your detail</p>

    <form action="{{ route('login') }}" method="POST">
        @csrf

        <div class="form-group-icon mb-3">
            <i class="bi bi-envelope icon"></i>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
        </div>

        <div class="form-group-icon mb-3">
            <i class="bi bi-lock icon"></i>
            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
            <i class="bi bi-eye-slash icon-toggle"></i>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3 small-text">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">
                    Remember me
                </label>
            </div>
            <a href="{{ route('password.forgot') }}">Forgot Password?</a>
        </div>

        <div class="mb-3 recaptcha-wrapper">
            <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
        </div>

        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary">Sign In</button>
        </div>

        <div class="text-center mt-4">
            <span class="text-muted">Don't have an account?</span> <a href="{{ route('register') }}">Sign Up</a>
        </div>
    </form>

</x-layouts.auth_layout>