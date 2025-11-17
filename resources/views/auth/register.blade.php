<x-layouts.auth_layout>

    <x-slot name="title">
        Sign Up
    </x-slot>

    <div class="auth-logo">
        Personal Blog
    </div>

    <h3 class="mb-2">Sign Up to your Account</h3>
    <p class="text-muted mb-4">Create an account to start writing.</p>

    <form action="{{ route('register') }}" method="POST">
        @csrf

        <div class="form-group-icon mb-3">
            <i class="bi bi-person icon"></i>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Username" required>
        </div>

        <div class="form-group-icon mb-3">
            <i class="bi bi-envelope icon"></i>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
        </div>

        <div class="form-group-icon mb-3">
            <i class="bi bi-lock icon"></i>
            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
            <i class="bi bi-eye-slash icon-toggle"></i>
        </div>
        <p class="text-muted small-text mb-3">Your password must have at least 8 characters.</p>

        <div class="form-group-icon mb-3">
            <i class="bi bi-lock icon"></i>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required>
            <i class="bi bi-eye-slash icon-toggle"></i>
        </div>

        <div class="mb-3 form-check small-text">
            <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
            <label class="form-check-label" for="terms">
                By creating an account means you agree to the
                <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms & Conditions</a>
                and our
                <a href="#" data-bs-toggle="modal" data-bs-target="#privacyModal">Privacy Policy</a>.
            </label>
        </div>

        <div class="mb-3 recaptcha-wrapper">
            <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
        </div>

        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary">Sign Up</button>
        </div>

        <div class="text-center mt-4">
            <span class="text-muted">Already have an account?</span> <a href="{{ route('login') }}">Sign In</a>
        </div>
    </form>

    <x-modal id="termsModal" title="Terms of Use">
        <p><strong>Last updated: November 14, 2025</strong></p>
        <p>Please read these Terms of Use ("Terms") carefully before using the Personal Blog website (the "Service").</p>
        <p>Your access to and use of the Service is conditioned on your acceptance of and compliance with these Terms. These Terms apply to all visitors, users, and others who access or use the Service.</p>

        <h5>1. Accounts</h5>
        <p>When you create an account with us, you must provide information that is accurate and complete. You are responsible for safeguarding the password that you use to access the Service.</p>
        <p>You agree not to disclose your password to any third party. You must notify us immediately upon becoming aware of any breach of security or unauthorized use of your account.</p>
        <p>You may not use as a username the name of another person or entity or that is not lawfully available for use.</p>
        <p>All accounts require <strong>email verification</strong> to be activated.</p>

        <h5>2. Content</h5>
        <p>Our Service allows you to post, link, store, share, and otherwise make available certain information, text, graphics, or other material ("Content"). You are responsible for the Content that you post to the Service, including its legality, reliability, and appropriateness.</p>
        <p>By posting Content to the Service, you grant us the right and license to host and display your Content on the Service (i.e., on your public blog page). You retain any and all of your rights to any Content you submit.</p>

        <h5>3. Prohibited Uses</h5>
        <p>You agree not to use the Service:</p>
        <ul>
            <li>In any way that violates any applicable local or international law.</li>
            <li>For the purpose of exploiting, harming, or attempting to exploit or harm minors in any way.</li>
            <li>To transmit any "junk mail", "spam", or any other similar solicitation.</li>
            <li>To post Content that is unlawful, obscene, defamatory, or threatening.</li>
            <li>To impersonate or attempt to impersonate the website owner or another user.</li>
        </ul>

        <h5>4. Termination</h5>
        <p>We may terminate or suspend your account immediately, without prior notice or liability, for any reason whatsoever, including without limitation if you breach the Terms.</p>

        <h5>5. Disclaimer</h5>
        <p>The Service is provided on an "AS IS" and "AS AVAILABLE" basis. We do not warrant that the service will be uninterrupted or error-free.</p>
    </x-modal>
    <x-modal id="privacyModal" title="Privacy Policy">
        <p><strong>Last updated: November 14, 2025</strong></p>
        <p>Welcome to Personal Blog ("we," "our," or "us"). We are committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website.</p>
        
        <h5>1. Information We Collect</h5>
        <p>We collect personally identifiable information that you voluntarily provide to us when you register on the website.</p>
        <ul>
            <li><strong>Personal Data:</strong> When you register, we collect your <strong>Name</strong>, <strong>Email Address</strong>, and an encrypted <strong>Password</strong>.</li>
            <li><strong>Usage Data:</strong> We may also collect information that your browser sends whenever you visit our Service.</li>
        </ul>

        <h5>2. How We Use Your Information</h5>
        <p>We use the information we collect primarily to provide, maintain, and improve our services. This includes:</p>
        <ul>
            <li>To create and manage your account.</li>
            <li>To authenticate you and allow you to log in.</li>
            <li>To send you a mandatory <strong>email verification</strong> link to secure your account.</li>
            <li>To send you a <strong>password reset</strong> link if you forget your password.</li>
            <li>To manage your created content (blog posts).</li>
        </ul>

        <h5>3. Security of Your Information</h5>
        <p>We use administrative, technical, and physical security measures to help protect your personal information. Your password is stored in an encrypted (hashed) format, meaning even we cannot see it.</p>

        <h5>4. Spam Prevention (reCAPTCHA)</h5>
        <p>This site is protected by reCAPTCHA v2. We use this service to verify that you are a human and not a bot to prevent spam on our registration and login forms. Your use of reCAPTCHA is subject to the Google Privacy Policy and Terms of Use.</p>

        <h5>5. Cookies</h5>
        <p>We use cookies to maintain your user session (to keep you logged in) and to remember your device if you check "Remember Me".</p>

        <h5>6. Your Rights</h5>
        <p>You have the right to access, update, or delete the information we have on you. You can manage your blog posts through your dashboard. If you wish to permanently delete your account, please contact us.</p>
    </x-modal>

</x-layouts.auth_layout>