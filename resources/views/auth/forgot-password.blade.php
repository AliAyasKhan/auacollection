<x-guest-layout>
    <div class="mb-4 text-muted small lh-lg">
        Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
    </div>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
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
            <a href="{{ route('login') }}" class="text-dark small fw-semibold text-decoration-none">
                Back to Sign In
            </a>
        </div>
    </form>
</x-guest-layout>
