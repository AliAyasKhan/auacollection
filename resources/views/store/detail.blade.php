@extends('layouts.store')

@section('title', $product->name . ' - AUA Collection')

@section('content')

    <!-- Breadcrumbs -->
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-luxury mb-0">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ url('/shop') }}">Shop</a></li>
                <li class="breadcrumb-item"><a href="{{ url('/shop?category=' . ($product->category->slug ?? '')) }}">{{ $product->category->name ?? 'Collection' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>
    </div>

    <!-- Product Detail Section -->
    <section class="pb-5">
        <div class="container">
            
            @if(session('success'))
                <div class="alert alert-success border-0 rounded-3 mb-4 text-center">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger border-0 rounded-3 mb-4 text-center">{{ session('error') }}</div>
            @endif

            <div class="row g-5">
                
                <!-- Product Gallery -->
                <div class="col-md-6">
                    <div id="productGallery" class="carousel slide" data-bs-ride="false">
                        <div class="carousel-inner bg-light rounded-4 overflow-hidden mb-3" style="max-height: 600px;">
                            @foreach($product->images as $index => $img)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    @if($img->image_path)
                                        <img src="{{ $img->url }}" class="d-block w-100 object-fit-cover" style="height: 550px;" alt="{{ $product->name }}">
                                    @else
                                        <div class="image-placeholder" style="height: 550px;">
                                            <span>{{ strtoupper(substr($product->name, 0, 3)) }} - IMAGE {{ $index + 1 }}</span>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Thumbnail indicators -->
                        @if($product->images->count() > 1)
                            <div class="d-flex gap-2 justify-content-center">
                                @foreach($product->images as $index => $img)
                                    <button type="button" data-bs-target="#productGallery" data-bs-slide-to="{{ $index }}" class="border-0 bg-transparent p-0 rounded-2 overflow-hidden" style="width: 80px; height: 80px;">
                                        @if($img->image_path)
                                            <img src="{{ $img->url }}" class="w-100 h-100 object-fit-cover" alt="Thumb">
                                        @else
                                            <div class="bg-secondary text-white w-100 h-100 d-flex align-items-center justify-content-center small" style="font-size: 0.65rem;">#{{ $index+1 }}</div>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Product Info & Add to Cart Panel -->
                <div class="col-md-6">
                    <div class="ps-md-4">
                        <span class="text-gold small letter-spacing-2 text-uppercase mb-2 d-block">{{ $product->brand->name ?? 'AUA Premium' }}</span>
                        <h1 class="font-serif display-5 mb-3">{{ $product->name }}</h1>
                        
                        <!-- Pricing -->
                        <div class="fs-4 mb-4 fw-semibold text-dark">
                            <span id="display-price">
                                @if($product->has_discount)
                                    <del class="text-muted fs-5 fw-normal me-2">{{ $storeCurrencySymbol }}{{ number_format($product->price) }}</del>
                                    <span>{{ $storeCurrencySymbol }}{{ number_format($product->discount_price) }}</span>
                                @else
                                    <span>{{ $storeCurrencySymbol }}{{ number_format($product->price) }}</span>
                                @endif
                            </span>
                        </div>

                        <div style="width: 60px; height: 1px; background-color: var(--color-accent-gold); mb-4;" class="mb-4"></div>

                        <p class="text-muted lh-lg mb-4">{{ $product->short_description }}</p>

                        <!-- Add to Cart Form -->
                        <form action="{{ route('cart.add') }}" method="POST" id="addToCartForm">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="product_variant_id" id="product_variant_id" value="">

                            <!-- Variant Color selection -->
                            @php
                                $variantColors = $product->variants->pluck('color')->unique()->filter();
                                $variantSizes = $product->variants->pluck('size')->unique()->filter();
                            @endphp

                            @if($variantColors->isNotEmpty())
                                <div class="mb-4">
                                    <label class="form-label mb-2">COLOR: <span id="selected-color-label" class="fw-semibold text-dark">Select Color</span></label>
                                    <div>
                                        @foreach($variantColors as $color)
                                            <div class="d-inline-block">
                                                <button type="button" class="variant-selector-color btn-color-dot" data-color-id="{{ $color->id }}" data-color-name="{{ $color->name }}" style="background-color: {{ $color->code }};"></button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Variant Size selection -->
                            @if($variantSizes->isNotEmpty())
                                <div class="mb-4">
                                    <label class="form-label mb-2">SIZE: <span id="selected-size-label" class="fw-semibold text-dark">Select Size</span></label>
                                    <div>
                                        @foreach($variantSizes as $size)
                                            <button type="button" class="variant-selector-size btn-size-box" data-size-id="{{ $size->id }}" data-size-code="{{ $size->code }}">{{ $size->code }}</button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- SKU and Stock Status -->
                            <div class="mb-4 small text-muted">
                                <span class="d-block mb-1">SKU: <span id="display-sku" class="text-dark fw-medium">{{ $product->SKU }}</span></span>
                                <span class="d-block">AVAILABILITY: 
                                    <span id="display-stock-status" class="fw-bold text-success">
                                        @if($product->stock > 0)
                                            In Stock ({{ $product->stock }} items)
                                        @else
                                            Out Of Stock
                                        @endif
                                    </span>
                                </span>
                            </div>

                            <!-- Quantity and Add to Cart CTA -->
                            <div class="row g-3 align-items-center mb-5">
                                <div class="col-sm-3 col-4">
                                    <div class="input-group bg-light rounded-3 px-1 py-1">
                                        <button type="button" class="btn btn-link text-dark border-0 p-1 fs-5 shadow-none" id="qty-minus"><i class="bi bi-dash"></i></button>
                                        <input type="number" name="quantity" id="quantity" class="form-control border-0 bg-transparent text-center shadow-none p-1 fw-bold font-monospace" value="1" min="1" max="100">
                                        <button type="button" class="btn btn-link text-dark border-0 p-1 fs-5 shadow-none" id="qty-plus"><i class="bi bi-plus"></i></button>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-8">
                                    <button type="submit" class="btn-luxury-dark w-100 py-3" id="btn-add-to-cart">ADD TO CART</button>
                                </div>
                                <div class="col-sm-3 col-12">
                                    <!-- Wishlist Button -->
                                    <button type="button" class="btn btn-outline-dark w-100 py-3" id="btn-wishlist-toggle">
                                        <i class="bi bi-heart"></i>
                                    </button>
                                </div>
                            </div>

                        </form>

                        <!-- Secondary details tabs -->
                        <div class="accordion accordion-flush" id="productSpecs">
                            <div class="accordion-item border-top border-bottom border-light">
                                <h2 class="accordion-header" id="flush-headingOne">
                                    <button class="accordion-button collapsed font-heading py-3 px-0 bg-transparent shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                        PRODUCT DETAILS
                                    </button>
                                </h2>
                                <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#productSpecs">
                                    <div class="accordion-body px-0 py-3 text-muted lh-lg">
                                        {{ $product->description }}
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <!-- Reviews Tab/Section -->
            <div class="mt-5 pt-5 border-top border-light">
                <h3 class="font-serif mb-4">Reviews ({{ $product->reviews->count() }})</h3>
                
                <div class="row g-5">
                    
                    <!-- Left: Reviews List -->
                    <div class="col-lg-6">
                        @if($product->reviews->isEmpty())
                            <p class="text-muted">No reviews yet. Be the first to share your thoughts on this product.</p>
                        @else
                            <div class="d-flex flex-column gap-4">
                                @foreach($product->reviews as $rev)
                                    <div class="pb-4 border-bottom border-light">
                                        <div class="d-flex justify-content-between mb-2">
                                            <h5 class="fs-6 mb-0">{{ $rev->user->name }}</h5>
                                            <span class="text-muted small">{{ $rev->created_at->format('d M, Y') }}</span>
                                        </div>
                                        <div class="text-gold mb-2" style="color: var(--color-accent-gold) !important;">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi bi-star{{ $i <= $rev->rating ? '-fill' : '' }}"></i>
                                            @endfor
                                        </div>
                                        <p class="text-muted small mb-0 lh-base">{{ $rev->comment }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Right: Review Submit Form -->
                    <div class="col-lg-6">
                        <div class="card border-0 bg-light p-4 rounded-4">
                            <h4 class="font-heading fs-5 mb-4">WRITE A REVIEW</h4>
                            @auth
                                <form action="{{ route('product.review') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Your Rating</label>
                                        <select name="rating" class="form-select border-0 py-2.5 px-3 rounded-3" required>
                                            <option value="">Select Stars...</option>
                                            <option value="5">5 Stars (Excellent)</option>
                                            <option value="4">4 Stars (Good)</option>
                                            <option value="3">3 Stars (Average)</option>
                                            <option value="2">2 Stars (Poor)</option>
                                            <option value="1">1 Star (Very Poor)</option>
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label">Review Comment</label>
                                        <textarea name="comment" rows="4" class="form-control border-0 py-2.5 px-3 rounded-3" placeholder="Describe your experience with this garment..."></textarea>
                                    </div>

                                    <button type="submit" class="btn-luxury-dark w-100 py-3">SUBMIT REVIEW</button>
                                </form>
                            @else
                                <div class="text-center py-4">
                                    <p class="text-muted mb-3">You must be logged in to leave product feedback.</p>
                                    <a href="{{ route('login') }}" class="btn-luxury-dark btn-sm">LOG IN</a>
                                </div>
                            @endauth
                        </div>
                    </div>

                </div>
            </div>

            <!-- Related Products Section -->
            @if($relatedProducts->isNotEmpty())
                <div class="mt-5 pt-5 border-top border-light">
                    <h3 class="font-serif mb-4 text-center">You May Also Like</h3>
                    <div class="row g-4">
                        @foreach($relatedProducts as $rel)
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="product-card">
                                    <div class="img-wrapper">
                                        @if($rel->has_discount)
                                            <span class="badge-luxury badge-sale">Sale</span>
                                        @endif
                                        <a href="{{ route('store.product.detail', $rel->slug) }}">
                                            @if($rel->primaryImage && $rel->primaryImage->image_path)
                                                <img src="{{ $rel->image_url }}" alt="{{ $rel->name }}">
                                            @else
                                                <div class="image-placeholder">
                                                    <span>{{ strtoupper(substr($rel->name, 0, 3)) }}</span>
                                                </div>
                                            @endif
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <div class="product-category">{{ $rel->category->name ?? 'Collection' }}</div>
                                        <h4 class="product-title">
                                            <a href="{{ route('store.product.detail', $rel->slug) }}">{{ $rel->name }}</a>
                                        </h4>
                                        <div class="product-price">
                                            @if($rel->has_discount)
                                                <del>{{ $storeCurrencySymbol }}{{ number_format($rel->price) }}</del>
                                                <span>{{ $storeCurrencySymbol }}{{ number_format($rel->discount_price) }}</span>
                                            @else
                                                <span>{{ $storeCurrencySymbol }}{{ number_format($rel->price) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </section>

    <!-- Wishlist submission mock form -->
    <form action="{{ route('wishlist.toggle') }}" method="POST" id="wishlistToggleForm" class="d-none">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">
    </form>

@endsection

@push('scripts')
<script>
    // Product Variant data for javascript mapper
    const variants = @json($product->variants);
    const productBasePrice = {{ $product->has_discount ? $product->discount_price : $product->price }};
    const productCurrencySymbol = "{{ $storeCurrencySymbol }}";
    
    let selectedColorId = null;
    let selectedSizeId = null;

    // Handles variant calculations
    function updateVariantSelection() {
        if (!variants || variants.length === 0) return;

        // If color/size exist but aren't selected yet, highlight requirements
        let activeVariant = null;

        // Match color and size
        if (variants.some(v => v.color_id) && !selectedColorId) {
            document.getElementById('product_variant_id').value = "";
            return;
        }
        if (variants.some(v => v.size_id) && !selectedSizeId) {
            document.getElementById('product_variant_id').value = "";
            return;
        }

        // Find the variant matching both criteria
        activeVariant = variants.find(v => {
            let colorMatch = v.color_id == selectedColorId;
            let sizeMatch = v.size_id == selectedSizeId;
            return colorMatch && sizeMatch;
        });

        const btnAdd = document.getElementById('btn-add-to-cart');
        const displayPrice = document.getElementById('display-price');
        const displayStock = document.getElementById('display-stock-status');
        const displaySku = document.getElementById('display-sku');

        if (activeVariant) {
            // Set variant input value
            document.getElementById('product_variant_id').value = activeVariant.id;
            
            // Calculate and show variant price
            let finalPrice = parseFloat(productBasePrice) + parseFloat(activeVariant.additional_price);
            displayPrice.innerHTML = `<span>${productCurrencySymbol}${finalPrice.toLocaleString()}</span>`;
            
            // SKU
            if (activeVariant.SKU) {
                displaySku.innerText = activeVariant.SKU;
            }

            // Stock
            if (activeVariant.stock > 0) {
                displayStock.className = "fw-bold text-success";
                displayStock.innerText = `In Stock (${activeVariant.stock} items)`;
                btnAdd.disabled = false;
                btnAdd.innerText = "ADD TO CART";
            } else {
                displayStock.className = "fw-bold text-danger";
                displayStock.innerText = "Out of Stock";
                btnAdd.disabled = true;
                btnAdd.innerText = "OUT OF STOCK";
            }
        } else {
            // No matching combination found
            document.getElementById('product_variant_id').value = "";
            displayStock.className = "fw-bold text-danger";
            displayStock.innerText = "Selected Combination Unavailable";
            btnAdd.disabled = true;
            btnAdd.innerText = "UNAVAILABLE";
        }
    }

    // Color buttons
    document.querySelectorAll('.btn-color-dot').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.btn-color-dot').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            selectedColorId = this.getAttribute('data-color-id');
            document.getElementById('selected-color-label').innerText = this.getAttribute('data-color-name');
            updateVariantSelection();
        });
    });

    // Size buttons
    document.querySelectorAll('.btn-size-box').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.btn-size-box').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            selectedSizeId = this.getAttribute('data-size-id');
            document.getElementById('selected-size-label').innerText = this.getAttribute('data-size-code');
            updateVariantSelection();
        });
    });

    // Qty plus minus
    const qtyInput = document.getElementById('quantity');
    document.getElementById('qty-plus').addEventListener('click', function() {
        qtyInput.value = parseInt(qtyInput.value) + 1;
    });
    document.getElementById('qty-minus').addEventListener('click', function() {
        if (parseInt(qtyInput.value) > 1) {
            qtyInput.value = parseInt(qtyInput.value) - 1;
        }
    });

    // Wishlist form submission
    document.getElementById('btn-wishlist-toggle').addEventListener('click', function() {
        @auth
            document.getElementById('wishlistToggleForm').submit();
        @else
            window.location.href = "{{ route('login') }}";
        @endauth
    });

    // Validate form on submit
    document.getElementById('addToCartForm').addEventListener('submit', function(e) {
        const variantInput = document.getElementById('product_variant_id').value;
        const colorExists = variants.some(v => v.color_id);
        const sizeExists = variants.some(v => v.size_id);

        if ((colorExists || sizeExists) && !variantInput) {
            e.preventDefault();
            alert("Please select both a valid Color and Size combination before checking out.");
        }
    });
</script>
@endpush
