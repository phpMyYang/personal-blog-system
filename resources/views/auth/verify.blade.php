<x-layouts.auth_layout>
    <x-slot name="title">Verify Your Email</x-slot>

    <div class="text-center">

        <div class="mb-3">
            <i class="bi bi-envelope-check-fill" style="font-size: 4rem; color: var(--color-primary);"></i>
        </div>

        <div class="auth-logo">Personal Blog</div>
        <h3 class="mb-2">Check Your Inbox</h3>

        <p class="text-muted">
            We sent a verification link to:
            <br><strong>{{ session('verification_email', 'your email') }}</strong>
        </p>
        <p class="text-muted">
            Please check your email (including Spam folder) to verify your account.
        </p>

        <hr class="my-4">

        <p class="text-muted small-text mb-2">
            Didn't receive the email?
        </p>

        <form action="{{ route('verification.resend') }}" method="POST" class="d-grid">
            @csrf
            <input type="hidden" name="email" value="{{ session('verification_email') }}">
            <button type="submit" class="btn btn-primary">Resend Verification Email</button>
        </form>

        <div class="text-center mt-4">
            <a href="{{ route('login') }}">Back to Sign In</a>
        </div>
    </div>

</x-layouts.auth_layout>