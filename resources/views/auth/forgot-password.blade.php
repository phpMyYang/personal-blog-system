<x-layouts.auth_layout>
    <x-slot name="title">Forgot Password</x-slot>

    <div class="auth-logo">Personal Blog</div>
    <h3 class="mb-2">Forgot Password</h3>
    <p class="text-muted mb-4">Enter your email to receive a reset link.</p>

    <form action="{{ route('password.email') }}" method="POST">
        @csrf

        <div class="form-group-icon mb-3">
            <i class="bi bi-envelope icon"></i>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
        </div>

        <div class="mb-3 recaptcha-wrapper">
            <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
        </div>

        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary">Send Reset Link</button>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('login') }}">Back to Sign In</a>
        </div>
    </form>
</x-layouts.auth_layout>