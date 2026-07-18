<x-guest-layout>
    <div class="text-center mb-4">
        <p class="small text-uppercase fw-semibold mb-1" style="letter-spacing: 2px;">Customer Login</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
        </div>

        <!-- Password -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label for="password" class="form-label mb-0">Password</label>
                @if (Route::has('password.request'))
                    <a class="small text-muted text-decoration-none" href="{{ route('password.request') }}" style="font-size: 0.75rem;">
                        Forgot password?
                    </a>
                @endif
            </div>
            <input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" />
        </div>

        <!-- Remember Me -->
        <div class="form-check mb-4">
            <input id="remember_me" type="checkbox" class="form-check-input" name="remember" style="accent-color: var(--color-primary-dark)">
            <label class="form-check-label small text-muted" for="remember_me">
                Remember my preferences
            </label>
        </div>

        <!-- Submit Button -->
        <div class="d-grid mb-4">
            <button type="submit" class="btn-luxury-dark py-2.5">
                SIGN IN
            </button>
        </div>

        <!-- Registration Link -->
        <div class="text-center">
            <p class="small text-muted mb-2">
                New to AUA Collection?
                <a href="{{ route('register') }}" class="text-dark fw-semibold text-decoration-none ms-1">
                    Create Account
                </a>
            </p>
            <p class="small text-muted mb-0">
                Staff member?
                <a href="{{ route('admin.login') }}" class="text-dark fw-semibold text-decoration-none ms-1">
                    Admin Login
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
