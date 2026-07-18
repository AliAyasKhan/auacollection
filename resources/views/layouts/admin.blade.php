<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard - AUA Collection')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --color-primary: #111111;
            --color-secondary: #1e1e1e;
            --color-accent-gold: #D4AF37;
            --color-light-gray: #f8f9fa;
            --color-border: #e9ecef;
            --font-serif: 'Montserrat', sans-serif;
            --font-sans: 'Inter', sans-serif;
        }

        body {
            font-family: var(--font-sans);
            background-color: #f5f6f8;
            color: #333333;
            overflow-x: hidden;
        }

        /* Sidebar Styling */
        .admin-sidebar {
            width: 260px;
            height: 100vh;
            background-color: var(--color-primary);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: all 0.3s;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            padding: 24px;
            font-family: var(--font-serif);
            font-weight: 700;
            font-size: 1.25rem;
            letter-spacing: 2px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .sidebar-brand a {
            color: #ffffff;
            text-decoration: none;
        }

        .sidebar-brand span {
            color: var(--color-accent-gold);
        }

        .sidebar-menu {
            list-style: none;
            padding: 20px 0;
            margin: 0;
            flex-grow: 1;
            overflow-y: auto;
        }

        .sidebar-menu li {
            margin-bottom: 5px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 24px;
            color: #b3b3b3;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .sidebar-menu a i {
            font-size: 1.1rem;
            margin-right: 12px;
            transition: all 0.2s;
        }

        .sidebar-menu a:hover, 
        .sidebar-menu li.active a {
            color: #ffffff;
            background-color: var(--color-secondary);
            border-left-color: var(--color-accent-gold);
        }

        .sidebar-menu a:hover i, 
        .sidebar-menu li.active a i {
            color: var(--color-accent-gold);
        }

        /* Topbar Styling */
        .admin-navbar {
            height: 70px;
            background-color: #ffffff;
            border-bottom: 1px solid var(--color-border);
            margin-left: 260px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        /* Main Content Styling */
        .admin-content {
            margin-left: 260px;
            padding: 30px;
            min-height: calc(100vh - 70px);
        }

        /* Cards & Buttons Custom */
        .card-luxury {
            background-color: #ffffff;
            border: 0;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            transition: transform 0.2s;
        }

        .card-stats {
            border-left: 4px solid var(--color-primary);
        }
        
        .card-stats.gold {
            border-left-color: var(--color-accent-gold);
        }

        .btn-luxury-dark {
            background-color: var(--color-primary);
            color: #ffffff;
            border: 1px solid var(--color-primary);
            border-radius: 6px;
            padding: 8px 18px;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .btn-luxury-dark:hover {
            background-color: #333333;
            color: #ffffff;
            border-color: #333333;
        }

        .btn-luxury-gold {
            background-color: var(--color-accent-gold);
            color: #111111;
            border: 1px solid var(--color-accent-gold);
            border-radius: 6px;
            padding: 8px 18px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .btn-luxury-gold:hover {
            background-color: #c5a12d;
            color: #000000;
        }

        .text-gold {
            color: var(--color-accent-gold);
        }

        .table-luxury th {
            font-family: var(--font-serif);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #777777;
            border-bottom: 2px solid var(--color-border);
            padding: 15px 10px;
        }

        .table-luxury td {
            padding: 15px 10px;
            font-size: 0.9rem;
            vertical-align: middle;
        }

        .badge-luxury {
            border-radius: 40px;
            font-weight: 600;
            padding: 5px 12px;
            font-size: 0.75rem;
            text-transform: uppercase;
        }

        /* Responsive */
        @media (max-width: 991.98px) {
            .admin-sidebar {
                left: -260px;
            }
            .admin-sidebar.active {
                left: 0;
            }
            .admin-navbar, .admin-content {
                margin-left: 0;
            }
            .admin-navbar {
                padding: 0 15px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Sidebar -->
    <div class="admin-sidebar" id="sidebar">
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}">
                AUA<span>ADMIN</span>
            </a>
        </div>
        <ul class="sidebar-menu">
            <li class="{{ Request::is('admin/dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-speedometer2"></i> DASHBOARD
                </a>
            </li>
            <li class="{{ Request::is('admin/products*') ? 'active' : '' }}">
                <a href="{{ route('admin.products.index') }}">
                    <i class="bi bi-box-seam"></i> PRODUCTS
                </a>
            </li>
            <li class="{{ Request::is('admin/categories*') ? 'active' : '' }}">
                <a href="{{ route('admin.categories.index') }}">
                    <i class="bi bi-tags"></i> CATEGORIES
                </a>
            </li>
            <li class="{{ Request::is('admin/orders*') ? 'active' : '' }}">
                <a href="{{ route('admin.orders.index') }}">
                    <i class="bi bi-receipt"></i> ORDERS
                </a>
            </li>
            <li class="{{ Request::is('admin/coupons*') ? 'active' : '' }}">
                <a href="{{ route('admin.coupons.index') }}">
                    <i class="bi bi-ticket-perforated"></i> COUPONS
                </a>
            </li>
            <li class="{{ Request::is('admin/banners*') ? 'active' : '' }}">
                <a href="{{ route('admin.banners.index') }}">
                    <i class="bi bi-images"></i> BANNERS
                </a>
            </li>
            <li class="{{ Request::is('admin/settings*') ? 'active' : '' }}">
                <a href="{{ route('admin.settings.index') }}">
                    <i class="bi bi-gear"></i> STORE SETTINGS
                </a>
            </li>
            <li class="mt-4 border-top border-secondary pt-3">
                <a href="{{ url('/') }}" target="_blank">
                    <i class="bi bi-shop"></i> VIEW STOREFRONT
                </a>
            </li>
        </ul>
        <div class="p-3 border-top border-secondary">
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm w-100 border-0 text-start text-white-50 p-2">
                    <i class="bi bi-box-arrow-right me-2 text-danger"></i> LOGOUT
                </button>
            </form>
        </div>
    </div>

    <!-- Navbar -->
    <div class="admin-navbar">
        <button class="btn btn-link text-dark d-lg-none p-0 fs-3" id="sidebar-toggle">
            <i class="bi bi-list"></i>
        </button>
        <div class="d-none d-md-block">
            <span class="text-muted small text-uppercase fw-semibold">AUA Collection back-office console</span>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="small fw-semibold text-dark">{{ auth()->user()->name }}</span>
            <span class="badge bg-dark badge-luxury text-gold">ADMIN</span>
        </div>
    </div>

    <!-- Main Content -->
    <div class="admin-content">
        <!-- Toast Alerts -->
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill me-2 fs-5 text-success"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5 text-danger"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('sidebar-toggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });
    </script>
    @stack('scripts')
</body>
</html>
