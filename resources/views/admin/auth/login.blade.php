<x-guest-layout>
    <div class="text-center mb-4">
        <p class="small text-uppercase fw-semibold mb-1" style="letter-spacing: 2px; color: var(--color-accent-gold);">Admin Portal</p>
        <p class="small text-muted mb-0">Sign in with your staff credentials</p>
    </div>

    <form method="POST" action="{{ route('admin.login.store') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
        </div>

        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label for="password" class="form-label mb-0">Password</label>
                <a class="small text-muted text-decoration-none" href="{{ route('admin.password.request') }}" style="font-size: 0.75rem;">
                    Forgot password?
                </a>
            </div>
            <input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" />
        </div>

        <div class="form-check mb-4">
            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
            <label class="form-check-label small text-muted" for="remember_me">
                Remember me
            </label>
        </div>

        <div class="d-grid mb-4">
            <button type="submit" class="btn-luxury-dark py-2.5">
                ADMIN SIGN IN
            </button>
        </div>

        <div class="text-center">
            <p class="small text-muted mb-0">
                Customer account?
                <a href="{{ route('login') }}" class="text-dark fw-semibold text-decoration-none ms-1">
                    Customer Login
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
