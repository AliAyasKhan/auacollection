<x-guest-layout>
    <div class="auth-form-header">
        <p class="auth-form-kicker">Customer Login</p>
        <h2 class="auth-form-title font-serif">Sign in to your account</h2>
        <p class="auth-form-subtitle">Use your email and password to continue.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="auth-form">
        @csrf

        <div class="auth-field">
            <label for="email" class="auth-label">Email Address</label>
            <div class="auth-input-wrap">
                <i class="bi bi-envelope"></i>
                <input
                    id="email"
                    class="auth-input"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="you@example.com"
                >
            </div>
        </div>

        <div class="auth-field">
            <div class="auth-label-row">
                <label for="password" class="auth-label mb-0">Password</label>
                @if (Route::has('password.request'))
                    <a class="auth-link-muted" href="{{ route('password.request') }}">Forgot password?</a>
                @endif
            </div>
            <div class="auth-input-wrap">
                <i class="bi bi-lock"></i>
                <input
                    id="password"
                    class="auth-input"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="Enter your password"
                >
            </div>
        </div>

        <div class="auth-check">
            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
            <label class="form-check-label" for="remember_me">Remember me</label>
        </div>

        <button type="submit" class="btn-luxury-dark auth-submit w-100">
            Sign In
        </button>

        <p class="auth-switch">
            New to AUA Collection?
            <a href="{{ route('register') }}">Create Account</a>
        </p>
    </form>
</x-guest-layout>
