<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'collection', 'primaryImage']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('SKU', 'like', "%{$search}%");
            });
        }

        $products = $query->latest()->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create', [
            'categories' => Category::all(),
            'collections' => Collection::all(),
            'brands' => Brand::all(),
            'colors' => Color::all(),
            'sizes' => Size::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'SKU' => 'required|string|unique:products,SKU',
            'category_id' => 'required|exists:categories,id',
            'collection_id' => 'nullable|exists:collections,id',
            'brand_id' => 'nullable|exists:brands,id',
            'stock' => 'required|integer|min:0',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'primary_image' => 'nullable|image|max:2048',
            'images.*' => 'nullable|image|max:2048',
            'status' => 'boolean',
            'featured' => 'boolean',
            'new_arrival' => 'boolean',
            'sale_product' => 'boolean',
        ]);

        $product = Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name).'-'.rand(1000, 9999),
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'SKU' => $request->SKU,
            'category_id' => $request->category_id,
            'collection_id' => $request->collection_id,
            'brand_id' => $request->brand_id,
            'stock' => $request->stock,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'barcode' => $request->barcode,
            'weight' => $request->weight,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'status' => $request->boolean('status'),
            'featured' => $request->boolean('featured'),
            'new_arrival' => $request->boolean('new_arrival'),
            'sale_product' => $request->boolean('sale_product'),
        ]);

        $this->storeImages($product, $request);
        $this->syncVariants($product, $request);
        $product->syncPrimaryImageColumn();

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit($id)
    {
        $product = Product::with(['images', 'variants'])->findOrFail($id);

        return view('admin.products.edit', [
            'product' => $product,
            'categories' => Category::all(),
            'collections' => Collection::all(),
            'brands' => Brand::all(),
            'colors' => Color::all(),
            'sizes' => Size::all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $product = Product::with('images')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'SKU' => 'required|string|unique:products,SKU,'.$product->id,
            'category_id' => 'required|exists:categories,id',
            'collection_id' => 'nullable|exists:collections,id',
            'brand_id' => 'nullable|exists:brands,id',
            'stock' => 'required|integer|min:0',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'primary_image' => 'nullable|file|mimes:jpg,jpeg,png,webp,gif|max:5120',
            'images' => 'nullable|array',
            'images.*' => 'nullable|file|mimes:jpg,jpeg,png,webp,gif|max:5120',
            'status' => 'boolean',
            'featured' => 'boolean',
            'new_arrival' => 'boolean',
            'sale_product' => 'boolean',
        ]);

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'SKU' => $request->SKU,
            'category_id' => $request->category_id,
            'collection_id' => $request->collection_id,
            'brand_id' => $request->brand_id,
            'stock' => $request->stock,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'barcode' => $request->barcode,
            'weight' => $request->weight,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'status' => $request->boolean('status'),
            'featured' => $request->boolean('featured'),
            'new_arrival' => $request->boolean('new_arrival'),
            'sale_product' => $request->boolean('sale_product'),
        ]);

        $imageUpdated = false;

        // 1) Dedicated main image field
        if ($request->hasFile('primary_image')) {
            $this->replacePrimaryImage($product, $request->file('primary_image'));
            $imageUpdated = true;
        }

        // 2) Gallery uploads — first file becomes main if main was not uploaded
        if ($request->hasFile('images')) {
            $galleryFiles = $request->file('images');
            foreach ($galleryFiles as $index => $image) {
                if (! $image) {
                    continue;
                }

                if (! $imageUpdated && $index === 0) {
                    $this->replacePrimaryImage($product, $image);
                    $imageUpdated = true;
                    continue;
                }

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $image->store('products', 'public'),
                    'is_primary' => false,
                ]);
            }
        }

        $this->syncVariants($product, $request);
        $product->refresh();
        $product->syncPrimaryImageColumn();

        $msg = 'Product updated successfully.';
        if ($imageUpdated) {
            $msg .= ' Main image saved to database: '.$product->fresh()->image;
        }

        return redirect()->route('admin.products.edit', $product->id)->with('success', $msg);
    }

    public function destroy($id)
    {
        $product = Product::with('images')->findOrFail($id);

        foreach ($product->images as $image) {
            $image->deleteFile();
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product soft deleted successfully.');
    }

    public function deleteImage($imageId)
    {
        $image = ProductImage::findOrFail($imageId);
        $product = $image->product;
        $wasPrimary = $image->is_primary;

        $image->deleteFile();
        $image->delete();

        if ($wasPrimary && $product) {
            $next = $product->images()->first();
            if ($next) {
                $next->update(['is_primary' => true]);
            } else {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => 'assets/images/placeholder.jpg',
                    'is_primary' => true,
                ]);
            }
            $product->syncPrimaryImageColumn();
        }

        return back()->with('success', 'Product image deleted successfully.');
    }

    protected function storeImages(Product $product, Request $request): void
    {
        $files = [];

        if ($request->hasFile('primary_image')) {
            $files[] = $request->file('primary_image');
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $files[] = $file;
            }
        }

        if (empty($files)) {
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => 'assets/images/placeholder.jpg',
                'is_primary' => true,
            ]);

            return;
        }

        foreach ($files as $index => $image) {
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $image->store('products', 'public'),
                'is_primary' => $index === 0,
            ]);
        }
    }

    protected function replacePrimaryImage(Product $product, $uploadedFile): void
    {
        $path = $uploadedFile->store('products', 'public');

        $primary = $product->images()->where('is_primary', true)->first();

        if ($primary) {
            $primary->deleteFile();
            $primary->update([
                'image_path' => $path,
                'is_primary' => true,
            ]);
        } else {
            $product->images()->update(['is_primary' => false]);
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $path,
                'is_primary' => true,
            ]);
        }

        // Keep products.image column in sync immediately
        $product->forceFill(['image' => $path])->saveQuietly();
    }

    protected function syncVariants(Product $product, Request $request): void
    {
        if (! $request->has('variants')) {
            return;
        }

        $product->variants()->delete();

        foreach ($request->variants as $variant) {
            if (! empty($variant['color_id']) || ! empty($variant['size_id'])) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'color_id' => $variant['color_id'] ?: null,
                    'size_id' => $variant['size_id'] ?: null,
                    'stock' => $variant['stock'] ?? 0,
                    'additional_price' => $variant['additional_price'] ?? 0.00,
                    'SKU' => $product->SKU.'-'.rand(10, 99),
                ]);
            }
        }
    }
}
