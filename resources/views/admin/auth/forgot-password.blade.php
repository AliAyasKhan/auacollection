<x-guest-layout>
    <div class="text-center mb-4">
        <p class="small text-uppercase fw-semibold mb-1" style="letter-spacing: 2px; color: var(--color-accent-gold);">Admin Portal</p>
        <p class="small text-muted mb-0">Reset your admin password</p>
    </div>

    <div class="mb-4 text-muted small lh-lg">
        Enter your admin email address and we will send a password reset link.
    </div>

    <form method="POST" action="{{ route('admin.password.email') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="form-label">Email Address</label>
            <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus />
        </div>

        <div class="d-grid gap-2 mb-3">
            <button type="submit" class="btn-luxury-dark py-2.5">
                EMAIL RESET LINK
            </button>
        </div>

        <div class="text-center">
            <a href="{{ route('admin.login') }}" class="text-dark small fw-semibold text-decoration-none">
                Back to Admin Sign In
            </a>
        </div>
    </form>
</x-guest-layout>
