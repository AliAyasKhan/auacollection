<x-guest-layout>
    <div class="mb-4 text-muted small lh-lg">
        Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success border-0 rounded-3 mb-4 small text-center">
            A new verification link has been sent to the email address you provided during registration.
        </div>
    @endif

    <div class="mt-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <div>
                <button type="submit" class="btn-luxury-dark btn-sm">
                    Resend Verification Email
                </button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-link text-muted text-decoration-none small p-0">
                Log Out
            </button>
        </form>
    </div>
</x-guest-layout>
