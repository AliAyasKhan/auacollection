<x-guest-layout>
    <div class="text-center mb-4">
        <p class="small text-uppercase fw-semibold mb-1" style="letter-spacing: 2px; color: var(--color-accent-gold);">Admin Portal</p>
        <p class="small text-muted mb-0">Choose a new password</p>
    </div>

    <form method="POST" action="{{ route('admin.password.store') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input id="email" class="form-control" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" />
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">New Password</label>
            <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" />
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label">Confirm New Password</label>
            <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" />
        </div>

        <div class="d-grid">
            <button type="submit" class="btn-luxury-dark py-2.5">
                RESET PASSWORD
            </button>
        </div>
    </form>
</x-guest-layout>
