<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $storeName . ' | Premium Luxury Fashion')</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('meta_description', 'Discover premium luxury clothing for Men, Women, and Kids at AUA Collection. Elevate your fashion with our elegant, minimal designs.')">
    @yield('meta_tags')

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Custom Style Guide -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    @stack('styles')
</head>
<body>

    <!-- Premium Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-luxury sticky-top">
        <div class="container">
            <!-- Brand -->
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ strtoupper(substr($storeName, 0, 3)) }}<span>{{ strtoupper(substr($storeName, 3)) }}</span>
            </a>

            <!-- Toggle Button for Mobile -->
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu Links -->
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 text-center">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ url('/') }}">HOME</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('shop') && !request('new_arrival') && !request('sale') ? 'active' : '' }}" href="{{ url('/shop') }}">SHOP</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('new_arrival') ? 'active' : '' }}" href="{{ url('/shop?new_arrival=1') }}">NEW ARRIVALS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('sale') ? 'active' : '' }}" href="{{ url('/shop?sale=1') }}">SALE</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('about') ? 'active' : '' }}" href="{{ url('/about') }}">ABOUT</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('contact') ? 'active' : '' }}" href="{{ url('/contact') }}">CONTACT</a>
                    </li>
                </ul>

                <!-- Utility Icons (Search, Account, Wishlist, Cart) -->
                <div class="d-flex align-items-center justify-content-center gap-3">
                    <!-- Search Trigger -->
                    <a href="#" class="text-dark fs-5" data-bs-toggle="modal" data-bs-target="#searchModal" id="btn-search-trigger">
                        <i class="bi bi-search"></i>
                    </a>

                    <!-- Account Dropdown -->
                    <div class="dropdown">
                        <a href="#" class="text-dark fs-5" id="accountDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm" aria-labelledby="accountDropdown">
                            @auth
                                @if(auth()->user()->isStaff())
                                    <li><a class="dropdown-item px-3 py-2 small text-gold fw-semibold" href="{{ route('admin.dashboard') }}">ADMIN PANEL</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('admin.logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item px-3 py-2 small text-danger">LOGOUT</button>
                                        </form>
                                    </li>
                                @else
                                    <li><a class="dropdown-item px-3 py-2 small" href="{{ url('/my-account') }}">MY ACCOUNT</a></li>
                                    <li><a class="dropdown-item px-3 py-2 small" href="{{ url('/my-orders') }}">MY ORDERS</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item px-3 py-2 small text-danger">LOGOUT</button>
                                        </form>
                                    </li>
                                @endif
                            @else
                                <li><a class="dropdown-item px-3 py-2 small" href="{{ route('login') }}">CUSTOMER LOGIN</a></li>
                                <li><a class="dropdown-item px-3 py-2 small" href="{{ route('register') }}">REGISTER</a></li>
                            @endauth
                        </ul>
                    </div>

                    <!-- Wishlist -->
                    <a href="{{ url('/wishlist') }}" class="text-dark fs-5 position-relative">
                        <i class="bi bi-heart"></i>
                        @if($globalWishlistCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark text-white font-monospace" style="font-size: 0.6rem;">
                                {{ $globalWishlistCount }}
                            </span>
                        @endif
                    </a>

                    <!-- Cart -->
                    <a href="{{ url('/cart') }}" class="text-dark fs-5 position-relative">
                        <i class="bi bi-bag"></i>
                        @if($globalCartCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark font-monospace fw-semibold" style="font-size: 0.6rem; background-color: var(--color-accent-gold) !important;">
                                {{ $globalCartCount }}
                            </span>
                        @endif
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer-luxury">
        <div class="container">
            <div class="row g-4">
                <!-- Brand Info -->
                <div class="col-md-4">
                    <h5 class="mb-4">{{ strtoupper($storeName) }}</h5>
                    <p class="text-white-50 lh-lg mb-4">
                        AUA Collection represents the pinnacle of luxury, minimal aesthetics, and pure comfort. Experience meticulously crafted attire suited for modern, elegant lifestyles.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="https://facebook.com" target="_blank" class="fs-5"><i class="bi bi-facebook"></i></a>
                        <a href="https://instagram.com" target="_blank" class="fs-5"><i class="bi bi-instagram"></i></a>
                        <a href="https://whatsapp.com" target="_blank" class="fs-5"><i class="bi bi-whatsapp"></i></a>
                        <a href="https://tiktok.com" target="_blank" class="fs-5"><i class="bi bi-tiktok"></i></a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="col-md-2 col-6 ms-md-auto">
                    <h5>COLLECTIONS</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ url('/shop?collection=summer-luxe') }}">Summer Luxe</a></li>
                        <li><a href="{{ url('/shop?collection=winter-glam') }}">Winter Glam</a></li>
                        <li><a href="{{ url('/shop?collection=heritage-classic') }}">Heritage Classic</a></li>
                        <li><a href="{{ url('/shop?new_arrival=1') }}">New Arrivals</a></li>
                    </ul>
                </div>

                <!-- Company Links -->
                <div class="col-md-2 col-6">
                    <h5>COMPANY</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ url('/about') }}">About Us</a></li>
                        <li><a href="{{ url('/contact') }}">Contact Us</a></li>
                        <li><a href="{{ url('/privacy-policy') }}">Privacy Policy</a></li>
                        <li><a href="{{ url('/terms-conditions') }}">Terms & Conditions</a></li>
                        <li><a href="{{ url('/faq') }}">FAQs</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="col-md-3">
                    <h5>VISIT US</h5>
                    <p class="text-white-50 mb-2"><i class="bi bi-geo-alt-fill me-2 text-gold"></i> {{ $storeAddress }}</p>
                    <p class="text-white-50 mb-2"><i class="bi bi-telephone-fill me-2 text-gold"></i> {{ $storePhone }}</p>
                    <p class="text-white-50 mb-3"><i class="bi bi-envelope-fill me-2 text-gold"></i> {{ $storeEmail }}</p>
                    <span class="badge border border-white-50 text-white-50 py-2 px-3 small">SECURE SSL PAYMENT</span>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-luxury-bottom text-center">
                <p class="mb-0">&copy; {{ date('Y') }} {{ $storeName }}. All Rights Reserved. Crafted for elegance.</p>
            </div>
        </div>
    </footer>

    <!-- Search Modal Overlay -->
    <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen-sm-down modal-lg">
            <div class="modal-content border-0">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-5 pb-5">
                    <h4 class="text-center font-heading mb-4">WHAT ARE YOU LOOKING FOR?</h4>
                    <form action="{{ url('/shop') }}" method="GET">
                        <div class="input-group border-bottom border-dark py-2">
                            <input type="text" name="search" class="form-control border-0 bg-transparent fs-4 shadow-none" placeholder="Search products..." aria-label="Search" autofocus>
                            <button class="btn btn-link text-dark border-0 p-0 fs-3" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                    <div class="mt-4 text-center">
                        <span class="text-muted small">Popular: </span>
                        <a href="{{ url('/shop?search=hoodie') }}" class="text-dark text-decoration-none mx-2 small">Hoodie</a>
                        <a href="{{ url('/shop?search=silk') }}" class="text-dark text-decoration-none mx-2 small">Silk Dress</a>
                        <a href="{{ url('/shop?search=sweater') }}" class="text-dark text-decoration-none mx-2 small">Sweater</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
