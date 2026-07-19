<x-guest-layout>
    <div class="auth-form-header">
        <p class="auth-form-kicker auth-form-kicker--admin">Admin Portal</p>
        <h2 class="auth-form-title font-serif">Staff sign in</h2>
        <p class="auth-form-subtitle">Enter your staff credentials to open the dashboard.</p>
    </div>

    <form method="POST" action="{{ route('admin.login.store') }}" class="auth-form">
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
                    placeholder="admin@auacollection.com"
                >
            </div>
        </div>

        <div class="auth-field">
            <div class="auth-label-row">
                <label for="password" class="auth-label mb-0">Password</label>
                <a class="auth-link-muted" href="{{ route('admin.password.request') }}">Forgot password?</a>
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

        <button type="submit" class="btn-luxury-gold auth-submit w-100">
            Admin Sign In
        </button>
    </form>
</x-guest-layout>
