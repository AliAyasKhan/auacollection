@extends('layouts.store')

@section('title', 'Shop - AUA Collection | Premium Luxury Fashion')

@section('content')

    <!-- Header Banner -->
    <section class="py-5 bg-dark text-white text-center" style="background: linear-gradient(135deg, #1C1C1E 0%, #000000 100%);">
        <div class="container py-4">
            <h1 class="font-serif display-4 mb-2">THE COLLECTION</h1>
            <p class="text-white-50 lead mb-0">Discover minimal luxury, masterfully tailored garments.</p>
        </div>
    </section>

    <!-- Breadcrumbs -->
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-luxury mb-0">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Shop</li>
            </ol>
        </nav>
    </div>

    <!-- Shop Content -->
    <section class="pb-5">
        <div class="container">
            <form action="{{ url('/shop') }}" method="GET" id="filterForm">
                <div class="row g-4">
                    
                    <!-- Sidebar Filters -->
                    <div class="col-lg-3">
                        <div class="card border-0 shadow-sm rounded-4 p-4">
                            <h4 class="font-heading fs-5 pb-2 mb-4 border-bottom border-light">FILTERS</h4>
                            
                            <!-- Search filter -->
                            <div class="mb-4">
                                <label class="form-label">SEARCH</label>
                                <div class="input-group bg-light rounded-3 px-2 py-1">
                                    <input type="text" name="search" class="form-control border-0 bg-transparent shadow-none" placeholder="Search..." value="{{ request('search') }}">
                                    <button class="btn btn-link text-dark p-0 border-0" type="submit"><i class="bi bi-search"></i></button>
                                </div>
                            </div>

                            <!-- Categories -->
                            <div class="mb-4">
                                <label class="form-label">CATEGORIES</label>
                                <div class="d-flex flex-column gap-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="category" id="cat-all" value="" {{ !request('category') ? 'checked' : '' }} onchange="this.form.submit()">
                                        <label class="form-check-label small" for="cat-all">All Categories</label>
                                    </div>
                                    @foreach($categories as $cat)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="category" id="cat-{{ $cat->slug }}" value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'checked' : '' }} onchange="this.form.submit()">
                                            <label class="form-check-label small" for="cat-{{ $cat->slug }}">{{ $cat->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Collections -->
                            <div class="mb-4">
                                <label class="form-label">COLLECTIONS</label>
                                <div class="d-flex flex-column gap-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="collection" id="col-all" value="" {{ !request('collection') ? 'checked' : '' }} onchange="this.form.submit()">
                                        <label class="form-check-label small" for="col-all">All Collections</label>
                                    </div>
                                    @foreach($collections as $col)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="collection" id="col-{{ $col->slug }}" value="{{ $col->slug }}" {{ request('collection') === $col->slug ? 'checked' : '' }} onchange="this.form.submit()">
                                            <label class="form-check-label small" for="col-{{ $col->slug }}">{{ $col->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Brands -->
                            <div class="mb-4">
                                <label class="form-label">BRANDS</label>
                                <div class="d-flex flex-column gap-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="brand" id="brand-all" value="" {{ !request('brand') ? 'checked' : '' }} onchange="this.form.submit()">
                                        <label class="form-check-label small" for="brand-all">All Brands</label>
                                    </div>
                                    @foreach($brands as $b)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="brand" id="brand-{{ $b->slug }}" value="{{ $b->slug }}" {{ request('brand') === $b->slug ? 'checked' : '' }} onchange="this.form.submit()">
                                            <label class="form-check-label small" for="brand-{{ $b->slug }}">{{ $b->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Sizes -->
                            <div class="mb-4">
                                <label class="form-label mb-3">SIZES</label>
                                <div>
                                    @foreach($sizes as $sz)
                                        <div class="form-check d-inline-block p-0 me-1">
                                            <input class="btn-check" type="checkbox" name="sizes[]" id="size-{{ $sz->id }}" value="{{ $sz->id }}" {{ is_array(request('sizes')) && in_array($sz->id, request('sizes')) ? 'checked' : '' }} onchange="this.form.submit()">
                                            <label class="variant-selector-size m-0 text-center" style="min-width: 44px;" for="size-{{ $sz->id }}">{{ $sz->code }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Colors -->
                            <div class="mb-4">
                                <label class="form-label mb-3">COLORS</label>
                                <div>
                                    @foreach($colors as $col)
                                        <div class="form-check d-inline-block p-0 me-1">
                                            <input class="btn-check" type="checkbox" name="colors[]" id="color-{{ $col->id }}" value="{{ $col->id }}" {{ is_array(request('colors')) && in_array($col->id, request('colors')) ? 'checked' : '' }} onchange="this.form.submit()">
                                            <label class="variant-selector-color m-0" style="background-color: {{ $col->code }};" for="color-{{ $col->id }}"></label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Price Range -->
                            <div class="mb-4">
                                <label class="form-label">PRICE RANGE</label>
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <input type="number" name="price_min" class="form-control bg-light border-0 py-2 fs-7" placeholder="Min" value="{{ request('price_min') }}">
                                    </div>
                                    <div class="col-6">
                                        <input type="number" name="price_max" class="form-control bg-light border-0 py-2 fs-7" placeholder="Max" value="{{ request('price_max') }}">
                                    </div>
                                </div>
                                <button type="submit" class="btn-luxury-dark w-100 py-2">APPLY PRICE</button>
                            </div>

                            <!-- Reset filters -->
                            <div>
                                <a href="{{ url('/shop') }}" class="btn btn-link text-muted small text-decoration-none w-100 text-center">RESET ALL FILTERS</a>
                            </div>

                        </div>
                    </div>

                    <!-- Products Catalog -->
                    <div class="col-lg-9">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <p class="text-muted mb-0 small">SHOWING {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} OF {{ $products->total() }} PRODUCTS</p>
                            
                            <!-- Sort dropdown -->
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-muted small text-nowrap">SORT BY</span>
                                <select name="sort_by" class="form-select border-0 bg-transparent shadow-none small fw-semibold" onchange="this.form.submit()">
                                    <option value="newest" {{ request('sort_by') == 'newest' ? 'selected' : '' }}>Newest</option>
                                    <option value="price_asc" {{ request('sort_by') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                                    <option value="price_desc" {{ request('sort_by') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                                    <option value="name_asc" {{ request('sort_by') == 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
                                    <option value="name_desc" {{ request('sort_by') == 'name_desc' ? 'selected' : '' }}>Name: Z to A</option>
                                </select>
                            </div>
                        </div>

                        <!-- Products Grid -->
                        @if($products->isEmpty())
                            <div class="text-center py-5">
                                <i class="bi bi-search fs-1 text-muted d-block mb-3"></i>
                                <h3 class="font-serif">No Products Found</h3>
                                <p class="text-muted">We couldn't find any products matching your select criteria. Try resetting filters.</p>
                                <a href="{{ url('/shop') }}" class="btn-luxury-dark mt-3">VIEW ALL PRODUCTS</a>
                            </div>
                        @else
                            <div class="row g-4 mb-5">
                                @foreach($products as $product)
                                    <div class="col-md-4 col-sm-6">
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

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center">
                                {{ $products->links() }}
                            </div>
                        @endif
                    </div>

                </div>
            </form>
        </div>
    </section>

@endsection
