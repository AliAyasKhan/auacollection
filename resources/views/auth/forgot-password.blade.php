<x-guest-layout>
    <div class="auth-form-header">
        <p class="auth-form-kicker">Password Reset</p>
        <h2 class="auth-form-title font-serif">Forgot your password?</h2>
        <p class="auth-form-subtitle">Enter your email and we will send a reset link.</p>
    </div>

    <form method="POST" action="{{ route('password.email') }}" class="auth-form">
        @csrf

        <div class="auth-field">
            <label for="email" class="auth-label">Email Address</label>
            <div class="auth-input-wrap">
                <i class="bi bi-envelope"></i>
                <input id="email" class="auth-input" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="you@example.com">
            </div>
        </div>

        <button type="submit" class="btn-luxury-dark auth-submit w-100">
            Email Reset Link
        </button>

        <p class="auth-switch">
            <a href="{{ route('login') }}">Back to Sign In</a>
        </p>
    </form>
</x-guest-layout>
