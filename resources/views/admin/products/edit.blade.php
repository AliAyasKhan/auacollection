@extends('layouts.admin')

@section('title', 'Edit Product - AUA Collection')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.products.index') }}" class="btn btn-link text-dark p-0 text-decoration-none mb-2"><i class="bi bi-arrow-left"></i> Back to Catalog</a>
    <h4 class="font-serif fw-bold text-uppercase letter-spacing-1">Edit Product: {{ $product->name }}</h4>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 rounded-3 mb-4">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger border-0 rounded-3 mb-4">
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row">
        <!-- Main Form Left -->
        <div class="col-lg-8">
            <div class="card card-luxury p-4">
                <h5 class="font-serif mb-4 fs-6 fw-bold text-uppercase border-bottom pb-2">Product Info</h5>
                
                <div class="mb-3">
                    <label for="name" class="form-label small fw-bold">Product Name *</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="SKU" class="form-label small fw-bold">SKU Code *</label>
                        <input type="text" name="SKU" id="SKU" class="form-control @error('SKU') is-invalid @enderror" value="{{ old('SKU', $product->SKU) }}" required>
                        @error('SKU')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="stock" class="form-label small fw-bold">Stock Quantity *</label>
                        <input type="number" name="stock" id="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', $product->stock) }}" min="0" required>
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="short_description" class="form-label small fw-bold">Short Summary Description</label>
                    <textarea name="short_description" id="short_description" rows="3" class="form-control @error('short_description') is-invalid @enderror">{{ old('short_description', $product->short_description) }}</textarea>
                    @error('short_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label small fw-bold">Detailed Specification Description</label>
                    <textarea name="description" id="description" rows="6" class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Dynamic Product Variants -->
            <div class="card card-luxury p-4">
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                    <h5 class="font-serif mb-0 fs-6 fw-bold text-uppercase">Product Variants</h5>
                    <button type="button" class="btn btn-outline-dark btn-sm" id="add-variant-btn"><i class="bi bi-plus-lg"></i> ADD VARIANT</button>
                </div>
                <p class="text-muted small">Specify customized variations (size, color, price offsets, individual stock levels) for this garment. Editing variants will replace previous ones on submit.</p>
                
                <div class="table-responsive">
                    <table class="table align-middle" id="variants-table">
                        <thead>
                            <tr class="small text-uppercase">
                                <th style="width: 25%;">Color</th>
                                <th style="width: 25%;">Size</th>
                                <th style="width: 20%;">Stock</th>
                                <th style="width: 20%;">Additional Price (Rs.)</th>
                                <th style="width: 10%;" class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody id="variants-tbody">
                            @foreach($product->variants as $index => $var)
                                <tr id="variant-row-{{ $index }}">
                                    <td>
                                        <select name="variants[{{ $index }}][color_id]" class="form-select form-select-sm">
                                            <option value="">None (Standard)</option>
                                            @foreach($colors as $color)
                                                <option value="{{ $color->id }}" {{ $var->color_id == $color->id ? 'selected' : '' }}>{{ $color->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="variants[{{ $index }}][size_id]" class="form-select form-select-sm">
                                            <option value="">None (Standard)</option>
                                            @foreach($sizes as $size)
                                                <option value="{{ $size->id }}" {{ $var->size_id == $size->id ? 'selected' : '' }}>{{ $size->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="variants[{{ $index }}][stock]" class="form-control form-control-sm" value="{{ $var->stock }}" min="0">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="variants[{{ $index }}][additional_price]" class="form-control form-control-sm" value="{{ $var->additional_price }}">
                                    </td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeVariantRow({{ $index }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar Right -->
        <div class="col-lg-4">
            <!-- Pricing Card -->
            <div class="card card-luxury p-4">
                <h5 class="font-serif mb-4 fs-6 fw-bold text-uppercase border-bottom pb-2">Pricing Structure</h5>
                <div class="mb-3">
                    <label for="price" class="form-label small fw-bold">Base Price (Rs.) *</label>
                    <input type="number" step="0.01" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $product->price) }}" required>
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="discount_price" class="form-label small fw-bold">Discounted Price (Rs.)</label>
                    <input type="number" step="0.01" name="discount_price" id="discount_price" class="form-control @error('discount_price') is-invalid @enderror" value="{{ old('discount_price', $product->discount_price) }}">
                    <span class="text-muted small fs-7">Must be less than Base Price. Leave blank if not on sale.</span>
                    @error('discount_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Associations Card -->
            <div class="card card-luxury p-4">
                <h5 class="font-serif mb-4 fs-6 fw-bold text-uppercase border-bottom pb-2">Category & Brand</h5>
                
                <div class="mb-3">
                    <label for="category_id" class="form-label small fw-bold">Category Assignment *</label>
                    <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="brand_id" class="form-label small fw-bold">Brand Tag</label>
                    <select name="brand_id" id="brand_id" class="form-select @error('brand_id') is-invalid @enderror">
                        <option value="">Select Brand</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                    @error('brand_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="collection_id" class="form-label small fw-bold">Designer Collection</label>
                    <select name="collection_id" id="collection_id" class="form-select @error('collection_id') is-invalid @enderror">
                        <option value="">Select Collection</option>
                        @foreach($collections as $collection)
                            <option value="{{ $collection->id }}" {{ old('collection_id', $product->collection_id) == $collection->id ? 'selected' : '' }}>{{ $collection->name }}</option>
                        @endforeach
                    </select>
                    @error('collection_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Current Images & Upload Media Card -->
            <div class="card card-luxury p-4">
                <h5 class="font-serif mb-4 fs-6 fw-bold text-uppercase border-bottom pb-2">Product Images</h5>

                <div class="mb-4">
                    <label class="form-label small fw-bold d-block">Current Main Image</label>
                    <img src="{{ $product->image_url }}" class="img-thumbnail rounded-3 mb-2" style="height: 120px; width: 120px; object-fit: cover;" alt="Primary">
                    <div class="small text-muted mb-2">DB path: <code>{{ $product->image ?: ($product->primaryImage->image_path ?? 'none') }}</code></div>
                    <label for="primary_image" class="form-label small fw-bold mt-2">Change Main Image *</label>
                    <input type="file" name="primary_image" id="primary_image" class="form-control @error('primary_image') is-invalid @enderror" accept="image/jpeg,image/png,image/webp,image/gif,.jpg,.jpeg,.png,.webp,.gif">
                    <span class="text-muted small fs-7 mt-1 d-block">Upload JPG/PNG/WEBP (max 5MB). This updates the image in SQL and on the shop.</span>
                    @error('primary_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                @if($product->images->count() > 0)
                    <div class="row g-2 mb-3">
                        <label class="form-label small fw-bold d-block mb-1">Gallery Images</label>
                        @foreach($product->images as $img)
                            <div class="col-4 position-relative">
                                <img src="{{ $img->url }}" class="img-thumbnail rounded-3" style="height: 70px; width: 100%; object-fit: cover;">
                                @if($img->is_primary)
                                    <span class="badge bg-dark position-absolute bottom-0 start-0 m-1" style="font-size: 0.55rem;">MAIN</span>
                                @endif
                                @if($product->images->count() > 1 && $img->image_path !== 'assets/images/placeholder.jpg')
                                    <button type="button" class="btn btn-danger btn-sm p-0 position-absolute top-0 end-0 rounded-circle" style="width: 20px; height: 20px; font-size: 0.7rem;" onclick="if(confirm('Delete image?')) { document.getElementById('delete-img-form-{{ $img->id }}').submit(); }">
                                        <i class="bi bi-x"></i>
                                    </button>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="mb-3">
                    <label for="images" class="form-label small fw-bold">Add Extra Gallery Images</label>
                    <input type="file" name="images[]" id="images" class="form-control @error('images') is-invalid @enderror" multiple accept="image/*">
                    <span class="text-muted small fs-7 mt-1 d-block">Adds more images without removing the main one. Max 2MB per file.</span>
                    @error('images')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Status Card -->
            <div class="card card-luxury p-4">
                <h5 class="font-serif mb-4 fs-6 fw-bold text-uppercase border-bottom pb-2">Publish Status</h5>
                
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="status" id="status" value="1" {{ old('status', $product->status) ? 'checked' : '' }}>
                    <label class="form-check-label small fw-bold" for="status">Active (Visible in Shop Catalog)</label>
                </div>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="featured" id="featured" value="1" {{ old('featured', $product->featured) ? 'checked' : '' }}>
                    <label class="form-check-label small fw-bold" for="featured">Featured Product Highlight</label>
                </div>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="new_arrival" id="new_arrival" value="1" {{ old('new_arrival', $product->new_arrival) ? 'checked' : '' }}>
                    <label class="form-check-label small fw-bold" for="new_arrival">New Arrival Tag</label>
                </div>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="sale_product" id="sale_product" value="1" {{ old('sale_product', $product->sale_product) ? 'checked' : '' }}>
                    <label class="form-check-label small fw-bold" for="sale_product">Mark as Sale Product</label>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="mb-4">
                <button type="submit" class="btn btn-luxury-gold w-100 py-3 mb-2 fw-bold">SAVE CHANGES</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-dark w-100 py-3">CANCEL</a>
            </div>
        </div>
    </div>
</form>

<!-- Hidden Forms to delete product images -->
@foreach($product->images as $img)
    <form action="{{ route('admin.products.images.delete', $img->id) }}" method="POST" id="delete-img-form-{{ $img->id }}" class="d-none">
        @csrf
        @method('DELETE')
    </form>
@endforeach

@endsection

@push('scripts')
<script>
    let variantCount = {{ $product->variants->count() }};
    
    const colors = {!! json_encode($colors) !!};
    const sizes = {!! json_encode($sizes) !!};
    const tbody = document.getElementById('variants-tbody');
    const addBtn = document.getElementById('add-variant-btn');

    function createVariantRow() {
        const row = document.createElement('tr');
        row.id = `variant-row-${variantCount}`;

        let colorOptions = '<option value="">None (Standard)</option>';
        colors.forEach(c => {
            colorOptions += `<option value="${c.id}">${c.name}</option>`;
        });

        let sizeOptions = '<option value="">None (Standard)</option>';
        sizes.forEach(s => {
            sizeOptions += `<option value="${s.id}">${s.name}</option>`;
        });

        row.innerHTML = `
            <td>
                <select name="variants[${variantCount}][color_id]" class="form-select form-select-sm">
                    ${colorOptions}
                </select>
            </td>
            <td>
                <select name="variants[${variantCount}][size_id]" class="form-select form-select-sm">
                    ${sizeOptions}
                </select>
            </td>
            <td>
                <input type="number" name="variants[${variantCount}][stock]" class="form-control form-control-sm" value="0" min="0">
            </td>
            <td>
                <input type="number" step="0.01" name="variants[${variantCount}][additional_price]" class="form-control form-control-sm" value="0.00">
            </td>
            <td class="text-end">
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeVariantRow(${variantCount})">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;

        tbody.appendChild(row);
        variantCount++;
    }

    function removeVariantRow(index) {
        const row = document.getElementById(`variant-row-${index}`);
        if(row) {
            row.remove();
        }
    }

    addBtn.addEventListener('click', createVariantRow);
</script>
@endpush
