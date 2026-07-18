<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input id="name" class="form-control" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" />
        </div>

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" />
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" />
        </div>

        <!-- Submit Button -->
        <div class="d-grid mb-4">
            <button type="submit" class="btn-luxury-dark py-2.5">
                CREATE ACCOUNT
            </button>
        </div>

        <!-- Login Link -->
        <div class="text-center">
            <p class="small text-muted mb-0">
                Already have an account?
                <a href="{{ route('login') }}" class="text-dark fw-semibold text-decoration-none ms-1">
                    Sign In
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
