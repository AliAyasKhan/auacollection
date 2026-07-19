<x-guest-layout>
    <div class="auth-form-header">
        <p class="auth-form-kicker">Create Account</p>
        <h2 class="auth-form-title font-serif">Join AUA Collection</h2>
        <p class="auth-form-subtitle">Create your customer account to shop and track orders.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="auth-form">
        @csrf

        <div class="auth-field">
            <label for="name" class="auth-label">Full Name</label>
            <div class="auth-input-wrap">
                <i class="bi bi-person"></i>
                <input id="name" class="auth-input" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Your full name">
            </div>
        </div>

        <div class="auth-field">
            <label for="email" class="auth-label">Email Address</label>
            <div class="auth-input-wrap">
                <i class="bi bi-envelope"></i>
                <input id="email" class="auth-input" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="you@example.com">
            </div>
        </div>

        <div class="auth-field">
            <label for="password" class="auth-label">Password</label>
            <div class="auth-input-wrap">
                <i class="bi bi-lock"></i>
                <input id="password" class="auth-input" type="password" name="password" required autocomplete="new-password" placeholder="Create a password">
            </div>
        </div>

        <div class="auth-field">
            <label for="password_confirmation" class="auth-label">Confirm Password</label>
            <div class="auth-input-wrap">
                <i class="bi bi-shield-lock"></i>
                <input id="password_confirmation" class="auth-input" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Repeat your password">
            </div>
        </div>

        <button type="submit" class="btn-luxury-dark auth-submit w-100">
            Create Account
        </button>

        <p class="auth-switch">
            Already have an account?
            <a href="{{ route('login') }}">Sign In</a>
        </p>
    </form>
</x-guest-layout>
