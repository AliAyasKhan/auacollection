<x-guest-layout>
    <div class="auth-form-header">
        <p class="auth-form-kicker">Password Reset</p>
        <h2 class="auth-form-title font-serif">Choose a new password</h2>
        <p class="auth-form-subtitle">Enter your email and a new password to continue.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="auth-form">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="auth-field">
            <label for="email" class="auth-label">Email Address</label>
            <div class="auth-input-wrap">
                <i class="bi bi-envelope"></i>
                <input id="email" class="auth-input" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
            </div>
        </div>

        <div class="auth-field">
            <label for="password" class="auth-label">New Password</label>
            <div class="auth-input-wrap">
                <i class="bi bi-lock"></i>
                <input id="password" class="auth-input" type="password" name="password" required autocomplete="new-password">
            </div>
        </div>

        <div class="auth-field">
            <label for="password_confirmation" class="auth-label">Confirm New Password</label>
            <div class="auth-input-wrap">
                <i class="bi bi-shield-lock"></i>
                <input id="password_confirmation" class="auth-input" type="password" name="password_confirmation" required autocomplete="new-password">
            </div>
        </div>

        <button type="submit" class="btn-luxury-dark auth-submit w-100">
            Reset Password
        </button>
    </form>
</x-guest-layout>
