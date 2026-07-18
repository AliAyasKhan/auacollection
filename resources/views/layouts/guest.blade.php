<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'AUA Collection') }}</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom Style Guide -->
    <link href="/css/custom.css" rel="stylesheet">
    
    <style>
        body {
            background-color: var(--color-gray-100);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }
        .auth-card {
            border: none;
            border-radius: var(--border-radius-md);
            box-shadow: var(--shadow-hover);
            background-color: var(--color-primary-light);
            max-width: 480px;
            width: 100%;
            padding: 3rem;
        }
        .auth-logo {
            font-family: var(--font-heading);
            font-weight: 600;
            letter-spacing: 4px;
            color: var(--color-primary-dark);
            text-decoration: none;
            display: block;
            text-align: center;
            font-size: 1.8rem;
            margin-bottom: 2.5rem;
        }
        .auth-logo span {
            color: var(--color-accent-gold);
        }
        .form-control {
            border: 1px solid var(--color-gray-200);
            border-radius: var(--border-radius-sm);
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            background-color: var(--color-gray-100);
            transition: all 0.2s ease;
        }
        .form-control:focus {
            background-color: var(--color-primary-light);
            border-color: var(--color-accent-gold);
            box-shadow: none;
        }
        .form-label {
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--color-gray-800);
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center">
        <div class="auth-card">
            <!-- Brand Logo -->
            <a class="auth-logo" href="{{ url('/') }}">
                AUA<span>COLLECTION</span>
            </a>

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="alert alert-danger border-0 rounded-3 mb-4 small">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Session Status -->
            @if (session('status'))
                <div class="alert alert-success border-0 rounded-3 mb-4 small text-center">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Page Content -->
            {{ $slot }}
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
