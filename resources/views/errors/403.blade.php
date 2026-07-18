<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Access Denied | AUA Collection</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/custom.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(160deg, #f7f5f2 0%, #ebe7e1 100%);
        }
        .denied-card {
            max-width: 480px;
            width: 100%;
            background: #fff;
            padding: 3rem 2.5rem;
            border-radius: 8px;
            box-shadow: 0 12px 40px rgba(0,0,0,.08);
            text-align: center;
        }
        .denied-code {
            font-size: 4rem;
            font-weight: 700;
            letter-spacing: 4px;
            color: #111;
            line-height: 1;
        }
        .denied-gold {
            color: #D4AF37;
        }
    </style>
</head>
<body>
    <div class="denied-card">
        <div class="denied-code mb-3">4<span class="denied-gold">0</span>3</div>
        <h1 class="h4 mb-3">Access Denied</h1>
        <p class="text-muted small mb-4 lh-lg">
            {{ $exception->getMessage() ?: 'You do not have permission to view this page.' }}
        </p>
        <div class="d-flex flex-column gap-2">
            @auth
                @if(auth()->user()->isStaff())
                    <a href="{{ route('admin.dashboard') }}" class="btn-luxury-dark py-2 text-decoration-none">Go to Admin Dashboard</a>
                @else
                    <a href="{{ route('account.dashboard') }}" class="btn-luxury-dark py-2 text-decoration-none">Go to My Account</a>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn-luxury-dark py-2 text-decoration-none">Customer Login</a>
                <a href="{{ route('admin.login') }}" class="btn btn-outline-dark py-2 text-decoration-none">Admin Login</a>
            @endauth
            <a href="{{ url('/') }}" class="small text-muted text-decoration-none mt-2">Back to Store</a>
        </div>
    </div>
</body>
</html>
