<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'AUA Collection'))</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/css/custom.css" rel="stylesheet">
</head>
@php
    $isAdminAuth = request()->routeIs('admin.*') || request()->is('login/admin');
@endphp
<body class="auth-page {{ $isAdminAuth ? 'auth-page--admin' : 'auth-page--customer' }}">
    <div class="auth-shell">
        <aside class="auth-brand-panel" aria-hidden="false">
            <div class="auth-brand-panel__overlay"></div>
            <div class="auth-brand-panel__content">
                <a href="{{ url('/') }}" class="auth-brand-mark">
                    AUA<span>COLLECTION</span>
                </a>
                <p class="auth-brand-eyebrow">{{ $isAdminAuth ? 'Staff Access' : 'Member Access' }}</p>
                <h1 class="auth-brand-headline font-serif">
                    {{ $isAdminAuth ? 'Manage the boutique with care.' : 'Welcome back to refined fashion.' }}
                </h1>
                <p class="auth-brand-copy">
                    {{ $isAdminAuth
                        ? 'Secure staff sign-in for orders, inventory, and store operations.'
                        : 'Sign in to continue shopping, track orders, and manage your wishlist.' }}
                </p>
            </div>
        </aside>

        <main class="auth-form-panel">
            <div class="auth-form-panel__inner">
                <a href="{{ url('/') }}" class="auth-mobile-brand d-lg-none">
                    AUA<span>COLLECTION</span>
                </a>

                @if ($errors->any())
                    <div class="auth-alert auth-alert--error" role="alert">
                        <i class="bi bi-exclamation-circle"></i>
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('status'))
                    <div class="auth-alert auth-alert--success" role="status">
                        <i class="bi bi-check-circle"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                {{ $slot }}

                <p class="auth-footer-note">
                    <a href="{{ url('/') }}">Return to store</a>
                </p>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
