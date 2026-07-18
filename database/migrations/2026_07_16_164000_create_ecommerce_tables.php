<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 2. Collections
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 3. Brands
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 4. Sizes
        Schema::create('sizes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., Medium
            $table->string('code')->unique(); // e.g., M
            $table->timestamps();
        });

        // 5. Colors
        Schema::create('colors', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., Black
            $table->string('code')->unique(); // e.g., #000000
            $table->timestamps();
        });

        // 6. Products
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('collection_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->string('SKU')->unique();
            $table->string('barcode')->nullable();
            $table->integer('stock')->default(0);
            $table->decimal('weight', 8, 2)->nullable();
            $table->boolean('status')->default(true);
            $table->boolean('featured')->default(false);
            $table->boolean('new_arrival')->default(false);
            $table->boolean('sale_product')->default(false);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // 7. Product Images
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('image_path');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        // 8. Product Variants
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('color_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('size_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('stock')->default(0);
            $table->decimal('additional_price', 10, 2)->default(0.00);
            $table->string('image_path')->nullable();
            $table->string('SKU')->nullable()->unique();
            $table->timestamps();
        });

        // 9. Carts
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('session_id')->nullable()->index();
            $table->timestamps();
        });

        // 10. Cart Items
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->cascadeOnDelete();
            $table->integer('quantity')->default(1);
            $table->timestamps();
        });

        // 11. Wishlists
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        // 12. Orders
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('order_number')->unique();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount', 10, 2)->default(0.00);
            $table->decimal('shipping_charges', 10, 2)->default(0.00);
            $table->decimal('tax', 10, 2)->default(0.00);
            $table->decimal('total', 10, 2);
            $table->string('status')->default('Pending'); // Pending, Payment Verified, Confirmed, Packing, Ready To Ship, Shipped, Out For Delivery, Delivered, Cancelled, Returned, Refunded
            $table->text('notes')->nullable();
            $table->string('tracking_number')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // 13. Order Items
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('SKU');
            $table->decimal('price', 10, 2);
            $table->integer('quantity');
            $table->timestamps();
        });

        // 14. Payments
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('transaction_id')->nullable();
            $table->string('payment_method'); // COD, Bank Transfer, Easypaisa, JazzCash
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('Pending'); // Pending, Approved, Rejected
            $table->string('proof_image')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 15. Shipping Addresses
        Schema::create('shipping_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->references('id')->on('users')->nullOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->string('address');
            $table->string('city');
            $table->string('postal_code');
            $table->string('country')->default('Pakistan');
            $table->timestamps();
        });

        // 16. Reviews
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->integer('rating'); // 1 to 5
            $table->text('comment')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 17. Coupons
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('type'); // fixed, percent
            $table->decimal('value', 10, 2);
            $table->text('description')->nullable();
            $table->decimal('min_spend', 10, 2)->nullable();
            $table->decimal('max_discount', 10, 2)->nullable();
            $table->date('expiry_date')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 18. Settings
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // 19. Banners
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('image_path');
            $table->string('link')->nullable();
            $table->string('button_text')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('shipping_addresses');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('wishlists');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('products');
        Schema::dropIfExists('colors');
        Schema::dropIfExists('sizes');
        Schema::dropIfExists('brands');
        Schema::dropIfExists('collections');
        Schema::dropIfExists('categories');
    }
};
