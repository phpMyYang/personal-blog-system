<x-layouts.auth_layout>
    <x-slot name="title">Reset Password</x-slot>

    <div class="auth-logo">Personal Blog</div>
    <h3 class="mb-2">Set a New Password</h3>
    <p class="text-muted mb-4">Please enter your new password.</p>

    <form action="{{ route('password.update') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group-icon mb-3">
            <i class="bi bi-envelope icon"></i>
            <input type="email" class="form-control" id="email" name="email" value="{{ $email }}" placeholder="Email" readonly>
        </div>

        <div class="form-group-icon mb-3">
            <i class="bi bi-lock icon"></i>
            <input type="password" class="form-control" id="password" name="password" placeholder="New Password" required>
            <i class="bi bi-eye-slash icon-toggle"></i>
        </div>

        <div class="form-group-icon mb-3">
            <i class="bi bi-lock icon"></i>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm New Password" required>
            <i class="bi bi-eye-slash icon-toggle"></i>
        </div>

        <div class="mb-3 recaptcha-wrapper">
            <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
        </div>

        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary">Update Password</button>
        </div>
    </form>
</x-layouts.auth_layout>