@extends('layouts.store')

@section('title', $storeName . ' | Premium Luxury Fashion & Clothing')

@section('content')

    <!-- Homepage Carousel -->
    @if($banners->isNotEmpty())
        <div id="luxuryCarousel" class="carousel slide carousel-fade carousel-luxury" data-bs-ride="carousel">
            <div class="carousel-indicators">
                @foreach($banners as $index => $banner)
                    <button type="button" data-bs-target="#luxuryCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}"></button>
                @endforeach
            </div>
            <div class="carousel-inner">
                @foreach($banners as $index => $banner)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <img src="{{ $banner->image_url }}" class="d-block w-100" alt="{{ $banner->title ?: 'AUA Banner' }}" style="object-fit: cover; max-height: 700px;">
                        <div class="carousel-caption d-flex flex-column justify-content-center">
                            <div class="container">
                                @if($banner->title)
                                    <h2 class="animate__animated animate__fadeInUp">{{ $banner->title }}</h2>
                                @endif
                                @if($banner->subtitle)
                                    <p class="animate__animated animate__fadeInUp animate__delay-1s">{{ $banner->subtitle }}</p>
                                @endif
                                @if($banner->link)
                                    <div class="animate__animated animate__fadeInUp animate__delay-2s">
                                        <a href="{{ url($banner->link) }}" class="btn-luxury-gold">{{ $banner->button_text ?: 'DISCOVER' }}</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#luxuryCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#luxuryCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    @endif

    <!-- Shop by Category -->
    <section class="py-5 category-section">
        <div class="container py-4">
            <div class="text-center mb-5">
                <span class="text-muted small letter-spacing-2 text-uppercase">Curation</span>
                <h2 class="font-serif mt-2 fs-1">Shop by Category</h2>
                <p class="text-muted mt-2 mb-0 mx-auto" style="max-width: 480px;">Explore our carefully curated collections for every member of the family.</p>
            </div>

            <div class="row justify-content-center g-4">
                @forelse($categories as $category)
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <a href="{{ url('/shop?category=' . $category->slug) }}" class="category-showcase-card">
                            <div class="category-showcase-media">
                                <img src="{{ $category->image_url }}?v={{ $category->updated_at?->timestamp }}" alt="{{ $category->name }}" loading="lazy">
                                <div class="category-showcase-overlay"></div>
                                <div class="category-showcase-label">
                                    <span class="category-showcase-eyebrow">Collection</span>
                                    <h3>{{ $category->name }}</h3>
                                    <span class="category-showcase-cta">Shop Now</span>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12 text-center text-muted py-4">
                        No categories available yet.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    @if($featured->isNotEmpty())
        <section class="py-5">
            <div class="container py-4">
                <div class="text-center mb-5">
                    <span class="text-gold small letter-spacing-2 text-uppercase">Exclusives</span>
                    <h2 class="font-serif mt-2 fs-1">Featured Products</h2>
                </div>
                <div class="row g-4">
                    @foreach($featured as $product)
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="product-card">
                                <div class="img-wrapper">
                                    @if($product->has_discount)
                                        <span class="badge-luxury badge-sale">Sale</span>
                                    @elseif($product->new_arrival)
                                        <span class="badge-luxury">New</span>
                                    @endif
                                    
                                    <a href="{{ route('store.product.detail', $product->slug) }}">
                                        @if($product->primaryImage && $product->primaryImage->image_path)
                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                        @else
                                            <div class="image-placeholder">
                                                <span>{{ strtoupper(substr($product->name, 0, 3)) }}</span>
                                            </div>
                                        @endif
                                    </a>
                                </div>
                                <div class="card-body">
                                    <div class="product-category">{{ $product->category->name ?? 'Collection' }}</div>
                                    <h4 class="product-title">
                                        <a href="{{ route('store.product.detail', $product->slug) }}">{{ $product->name }}</a>
                                    </h4>
                                    <div class="product-price">
                                        @if($product->has_discount)
                                            <del>{{ $storeCurrencySymbol }}{{ number_format($product->price) }}</del>
                                            <span>{{ $storeCurrencySymbol }}{{ number_format($product->discount_price) }}</span>
                                        @else
                                            <span>{{ $storeCurrencySymbol }}{{ number_format($product->price) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="text-center mt-5">
                    <a href="{{ url('/shop') }}" class="btn-luxury-outline">VIEW ALL PRODUCTS</a>
                </div>
            </div>
        </section>
    @endif

    <!-- Premium Editorial Banner -->
    <section class="py-5" style="background-color: var(--color-gray-100);">
        <div class="container py-5">
            <div class="row align-items-center g-5">
                <div class="col-md-6">
                    <div class="p-lg-5">
                        <span class="text-gold small letter-spacing-2 text-uppercase">The Philosophy</span>
                        <h2 class="font-serif mt-2 mb-4 display-5">Pure Minimalism. Luxury Comfort.</h2>
                        <p class="text-muted lh-lg mb-4">
                            We believe that fashion should be a statement of effortless elegance. Every piece in our AUA collection is engineered using hand-selected organic cottons, pure Mulberry silks, and Grade-A Mongolian cashmeres to define a timeless silhouette.
                        </p>
                        <a href="{{ url('/about') }}" class="btn-luxury-dark">OUR HERITAGE</a>
                    </div>
                </div>
                <div class="col-md-6 text-center">
                    <div class="bg-dark rounded-4 shadow-lg overflow-hidden d-inline-block" style="width: 100%; max-width: 500px; height: 500px; background: linear-gradient(135deg, #1C1C1E 0%, #000000 100%); position: relative;">
                        <!-- Editorial Graphic or text styling -->
                        <div class="position-absolute top-50 start-50 translate-middle text-center w-75">
                            <h3 class="text-white font-serif fs-1 mb-3">AUA</h3>
                            <div style="width: 50px; height: 1px; background-color: var(--color-accent-gold); margin: 0 auto 1.5rem;"></div>
                            <p class="text-white-50 small letter-spacing-2">FALL / WINTER COLLECTION 2026</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- New Arrivals Section -->
    @if($newArrivals->isNotEmpty())
        <section class="py-5">
            <div class="container py-4">
                <div class="text-center mb-5">
                    <span class="text-gold small letter-spacing-2 text-uppercase">Latest Releases</span>
                    <h2 class="font-serif mt-2 fs-1">New Arrivals</h2>
                </div>
                <div class="row g-4">
                    @foreach($newArrivals as $product)
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="product-card">
                                <div class="img-wrapper">
                                    <span class="badge-luxury">New</span>
                                    <a href="{{ route('store.product.detail', $product->slug) }}">
                                        @if($product->primaryImage && $product->primaryImage->image_path)
                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                        @else
                                            <div class="image-placeholder">
                                                <span>{{ strtoupper(substr($product->name, 0, 3)) }}</span>
                                            </div>
                                        @endif
                                    </a>
                                </div>
                                <div class="card-body">
                                    <div class="product-category">{{ $product->category->name ?? 'Collection' }}</div>
                                    <h4 class="product-title">
                                        <a href="{{ route('store.product.detail', $product->slug) }}">{{ $product->name }}</a>
                                    </h4>
                                    <div class="product-price">
                                        @if($product->has_discount)
                                            <del>{{ $storeCurrencySymbol }}{{ number_format($product->price) }}</del>
                                            <span>{{ $storeCurrencySymbol }}{{ number_format($product->discount_price) }}</span>
                                        @else
                                            <span>{{ $storeCurrencySymbol }}{{ number_format($product->price) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

@endsection
