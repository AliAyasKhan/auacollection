<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input id="email" class="form-control" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" />
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">New Password</label>
            <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" />
        </div>

        <!-- Confirm Password -->
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
