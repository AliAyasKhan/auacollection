@extends('layouts.store')

@section('title', 'Shop - AUA Collection | Premium Luxury Fashion')

@section('content')

    @php
        $heroEyebrow = 'Boutique';
        $heroTitle = 'The Collection';
        $heroLede = 'Discover minimal luxury and masterfully tailored garments.';
        $crumbCurrent = 'Shop';

        if (request()->filled('sale')) {
            $heroEyebrow = 'Offers';
            $heroTitle = 'Sale';
            $heroLede = 'Selected pieces, thoughtfully reduced for a limited time.';
            $crumbCurrent = 'Sale';
        } elseif (request()->filled('new_arrival')) {
            $heroEyebrow = 'Just In';
            $heroTitle = 'New Arrivals';
            $heroLede = 'Fresh silhouettes newly added to the boutique.';
            $crumbCurrent = 'New Arrivals';
        } elseif (request()->filled('category')) {
            $selectedCategory = $categories->firstWhere('slug', request('category'));
            $heroEyebrow = 'Category';
            $heroTitle = $selectedCategory->name ?? 'Collection';
            $heroLede = 'Explore refined pieces curated for this category.';
            $crumbCurrent = $heroTitle;
        } elseif (request()->filled('collection')) {
            $selectedCollection = $collections->firstWhere('slug', request('collection'));
            $heroEyebrow = 'Collection';
            $heroTitle = $selectedCollection->name ?? 'Collection';
            $heroLede = 'A curated edit from our signature collections.';
            $crumbCurrent = $heroTitle;
        } elseif (request()->filled('search')) {
            $heroEyebrow = 'Search';
            $heroTitle = 'Search Results';
            $heroLede = 'Showing matches for “'.request('search').'”.';
            $crumbCurrent = 'Search';
        }

        $hasActiveFilters = request()->filled('category')
            || request()->filled('collection')
            || request()->filled('brand')
            || request()->filled('search')
            || request()->filled('price_min')
            || request()->filled('price_max')
            || request()->filled('sizes')
            || request()->filled('colors');

        $sortOptions = [
            'newest' => 'Newest',
            'price_asc' => 'Price: Low to High',
            'price_desc' => 'Price: High to Low',
            'name_asc' => 'Name: A to Z',
            'name_desc' => 'Name: Z to A',
        ];
        $currentSort = request('sort_by', 'newest');
        if (! array_key_exists($currentSort, $sortOptions)) {
            $currentSort = 'newest';
        }
    @endphp

    <section class="collection-hero">
        <div class="collection-hero__veil"></div>
        <div class="container collection-hero__content">
            <p class="collection-hero__eyebrow">{{ $heroEyebrow }}</p>
            <h1 class="collection-hero__title font-serif">{{ $heroTitle }}</h1>
            <p class="collection-hero__lede">{{ $heroLede }}</p>
            <span class="collection-hero__rule" aria-hidden="true"></span>
        </div>
    </section>

    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-luxury mb-0">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                @if($crumbCurrent === 'Shop')
                    <li class="breadcrumb-item active" aria-current="page">Shop</li>
                @else
                    <li class="breadcrumb-item"><a href="{{ url('/shop') }}">Shop</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $crumbCurrent }}</li>
                @endif
            </ol>
        </nav>
    </div>

    <section class="pb-5 shop-catalog-section">
        <div class="container">
            <form action="{{ url('/shop') }}" method="GET" id="filterForm">
                @if(request()->filled('sale'))
                    <input type="hidden" name="sale" value="1">
                @endif
                @if(request()->filled('new_arrival'))
                    <input type="hidden" name="new_arrival" value="1">
                @endif

                <div class="shop-toolbar">
                    <button type="button" class="shop-toolbar__btn" id="toggleFilters" aria-expanded="false" aria-controls="shopFiltersPanel">
                        <i class="bi bi-funnel"></i>
                        <span id="toggleFiltersLabel">Show Filters</span>
                        @if($hasActiveFilters)
                            <span class="shop-toolbar__dot" aria-hidden="true"></span>
                        @endif
                    </button>

                    <p class="shop-toolbar__count mb-0">
                        {{ number_format($products->total()) }} {{ \Illuminate\Support\Str::plural('Product', $products->total()) }}
                    </p>

                    <div class="shop-sort" id="shopSort">
                        <input type="hidden" name="sort_by" id="sort_by" value="{{ $currentSort }}">
                        <button type="button" class="shop-sort__toggle" id="shopSortToggle" aria-haspopup="listbox" aria-expanded="false" aria-controls="shopSortMenu">
                            <i class="bi bi-sort-down" aria-hidden="true"></i>
                            <span id="shopSortLabel">{{ $sortOptions[$currentSort] }}</span>
                            <i class="bi bi-chevron-down shop-sort__chevron" aria-hidden="true"></i>
                        </button>
                        <ul class="shop-sort__menu" id="shopSortMenu" role="listbox" hidden>
                            @foreach($sortOptions as $value => $label)
                                <li role="option">
                                    <button
                                        type="button"
                                        class="shop-sort__option {{ $currentSort === $value ? 'is-active' : '' }}"
                                        data-value="{{ $value }}"
                                        data-label="{{ $label }}"
                                        aria-selected="{{ $currentSort === $value ? 'true' : 'false' }}"
                                    >
                                        {{ $label }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="row g-4 shop-layout" id="shopLayout">
                    <aside class="col-lg-3 shop-filters-col d-none" id="shopFiltersPanel">
                        <div class="shop-filters">
                            <div class="shop-filters__head">
                                <h2 class="shop-filters__title">Filters</h2>
                                <a href="{{ url('/shop') }}{{ request()->filled('sale') ? '?sale=1' : (request()->filled('new_arrival') ? '?new_arrival=1' : '') }}" class="shop-filters__reset">Clear all</a>
                            </div>

                            <div class="shop-filter-group">
                                <label class="shop-filter-label" for="shop-search">Search</label>
                                <div class="shop-search">
                                    <input id="shop-search" type="text" name="search" class="shop-search__input" placeholder="Search Collection" value="{{ request('search') }}">
                                    <button class="shop-search__btn" type="submit" aria-label="Search"><i class="bi bi-search"></i></button>
                                </div>
                            </div>

                            <div class="shop-filter-group">
                                <p class="shop-filter-label">Categories</p>
                                <div class="shop-filter-list">
                                    <label class="shop-check">
                                        <input type="radio" name="category" value="" {{ !request('category') ? 'checked' : '' }} onchange="this.form.submit()">
                                        <span>All Categories</span>
                                    </label>
                                    @foreach($categories as $cat)
                                        <label class="shop-check">
                                            <input type="radio" name="category" value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'checked' : '' }} onchange="this.form.submit()">
                                            <span>{{ $cat->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="shop-filter-group">
                                <p class="shop-filter-label">Collections</p>
                                <div class="shop-filter-list">
                                    <label class="shop-check">
                                        <input type="radio" name="collection" value="" {{ !request('collection') ? 'checked' : '' }} onchange="this.form.submit()">
                                        <span>All Collections</span>
                                    </label>
                                    @foreach($collections as $col)
                                        <label class="shop-check">
                                            <input type="radio" name="collection" value="{{ $col->slug }}" {{ request('collection') === $col->slug ? 'checked' : '' }} onchange="this.form.submit()">
                                            <span>{{ $col->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="shop-filter-group">
                                <p class="shop-filter-label">Brands</p>
                                <div class="shop-filter-list">
                                    <label class="shop-check">
                                        <input type="radio" name="brand" value="" {{ !request('brand') ? 'checked' : '' }} onchange="this.form.submit()">
                                        <span>All Brands</span>
                                    </label>
                                    @foreach($brands as $b)
                                        <label class="shop-check">
                                            <input type="radio" name="brand" value="{{ $b->slug }}" {{ request('brand') === $b->slug ? 'checked' : '' }} onchange="this.form.submit()">
                                            <span>{{ $b->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="shop-filter-group">
                                <p class="shop-filter-label">Sizes</p>
                                <div class="shop-size-grid">
                                    @foreach($sizes as $sz)
                                        <div>
                                            <input class="btn-check" type="checkbox" name="sizes[]" id="size-{{ $sz->id }}" value="{{ $sz->id }}" {{ is_array(request('sizes')) && in_array($sz->id, request('sizes')) ? 'checked' : '' }} onchange="this.form.submit()">
                                            <label class="shop-size-chip" for="size-{{ $sz->id }}">{{ $sz->code }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="shop-filter-group">
                                <p class="shop-filter-label">Colors</p>
                                <div class="shop-color-grid">
                                    @foreach($colors as $col)
                                        <div>
                                            <input class="btn-check" type="checkbox" name="colors[]" id="color-{{ $col->id }}" value="{{ $col->id }}" {{ is_array(request('colors')) && in_array($col->id, request('colors')) ? 'checked' : '' }} onchange="this.form.submit()">
                                            <label class="shop-color-chip" style="--swatch: {{ $col->code }};" for="color-{{ $col->id }}" title="{{ $col->name }}"></label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="shop-filter-group mb-0">
                                <p class="shop-filter-label">Price Range</p>
                                <div class="shop-price-row">
                                    <input type="number" name="price_min" class="shop-price-input" placeholder="Min" value="{{ request('price_min') }}">
                                    <span class="shop-price-sep">–</span>
                                    <input type="number" name="price_max" class="shop-price-input" placeholder="Max" value="{{ request('price_max') }}">
                                </div>
                                <button type="submit" class="btn-luxury-dark w-100 mt-3 shop-price-apply">Apply Price</button>
                            </div>
                        </div>
                    </aside>

                    <div class="col-12" id="shopCatalogCol">
                        @if($products->isEmpty())
                            <div class="text-center py-5">
                                <i class="bi bi-search fs-1 text-muted d-block mb-3"></i>
                                <h3 class="font-serif">No Products Found</h3>
                                <p class="text-muted">We couldn't find any products matching your selected criteria. Try resetting filters.</p>
                                <a href="{{ url('/shop') }}" class="btn-luxury-dark mt-3">View All Products</a>
                            </div>
                        @else
                            <div class="row g-4 mb-5" id="shopProductGrid">
                                @foreach($products as $product)
                                    <div class="col-lg-3 col-md-4 col-sm-6 shop-product-col">
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

@push('scripts')
<script>
    (function () {
        const toggle = document.getElementById('toggleFilters');
        const panel = document.getElementById('shopFiltersPanel');
        const catalog = document.getElementById('shopCatalogCol');
        const label = document.getElementById('toggleFiltersLabel');
        const layout = document.getElementById('shopLayout');
        const cols = document.querySelectorAll('.shop-product-col');

        if (toggle && panel && catalog) {
            toggle.addEventListener('click', function () {
                const open = panel.classList.contains('d-none');

                panel.classList.toggle('d-none', !open);
                toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
                label.textContent = open ? 'Hide Filters' : 'Show Filters';
                layout.classList.toggle('shop-layout--filters-open', open);

                catalog.classList.toggle('col-lg-9', open);
                catalog.classList.toggle('col-12', !open);

                cols.forEach(function (col) {
                    col.classList.toggle('col-md-4', true);
                    col.classList.toggle('col-sm-6', true);
                    if (open) {
                        col.classList.remove('col-lg-3');
                    } else {
                        col.classList.add('col-lg-3');
                    }
                });
            });
        }

        const sortRoot = document.getElementById('shopSort');
        const sortToggle = document.getElementById('shopSortToggle');
        const sortMenu = document.getElementById('shopSortMenu');
        const sortInput = document.getElementById('sort_by');
        const sortLabel = document.getElementById('shopSortLabel');
        const sortForm = document.getElementById('filterForm');

        if (!sortRoot || !sortToggle || !sortMenu || !sortInput || !sortLabel || !sortForm) return;

        function closeSort() {
            sortMenu.hidden = true;
            sortRoot.classList.remove('is-open');
            sortToggle.setAttribute('aria-expanded', 'false');
        }

        function openSort() {
            sortMenu.hidden = false;
            sortRoot.classList.add('is-open');
            sortToggle.setAttribute('aria-expanded', 'true');
        }

        sortToggle.addEventListener('click', function (e) {
            e.stopPropagation();
            if (sortMenu.hidden) {
                openSort();
            } else {
                closeSort();
            }
        });

        sortMenu.querySelectorAll('.shop-sort__option').forEach(function (option) {
            option.addEventListener('click', function () {
                sortInput.value = option.getAttribute('data-value');
                sortLabel.textContent = option.getAttribute('data-label');

                sortMenu.querySelectorAll('.shop-sort__option').forEach(function (el) {
                    el.classList.remove('is-active');
                    el.setAttribute('aria-selected', 'false');
                });
                option.classList.add('is-active');
                option.setAttribute('aria-selected', 'true');

                closeSort();
                sortForm.submit();
            });
        });

        document.addEventListener('click', function (e) {
            if (!sortRoot.contains(e.target)) {
                closeSort();
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeSort();
            }
        });
    })();
</script>
@endpush
