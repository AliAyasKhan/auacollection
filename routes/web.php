<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Storefront Public Routes
use App\Http\Controllers\StoreController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\WishlistController;

// Store General pages
Route::get('/', [StoreController::class, 'home'])->name('store.home');
Route::get('/shop', [StoreController::class, 'shop'])->name('store.shop');
Route::get('/product/{slug}', [StoreController::class, 'detail'])->name('store.product.detail');
Route::get('/about', [StoreController::class, 'about'])->name('store.about');
Route::get('/contact', [StoreController::class, 'contact'])->name('store.contact');
Route::post('/contact', [StoreController::class, 'handleContactSubmit'])->name('store.contact.submit');
Route::get('/faq', [StoreController::class, 'faq'])->name('store.faq');
Route::get('/privacy-policy', [StoreController::class, 'privacyPolicy'])->name('store.privacy');
Route::get('/terms-conditions', [StoreController::class, 'termsConditions'])->name('store.terms');

// Tracking Route (publicly accessible)
Route::get('/track-order', [AccountController::class, 'trackOrder'])->name('account.track');

// Shopping Cart Routes (session-based)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Authenticated Customer Routes
Route::middleware(['auth', 'customer'])->group(function () {
    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/coupon', [CheckoutController::class, 'applyCoupon'])->name('checkout.coupon');
    Route::post('/checkout/place', [CheckoutController::class, 'placeOrder'])->name('checkout.place');
    Route::get('/checkout/success/{order_number}', [CheckoutController::class, 'success'])->name('checkout.success');

    // Account & Orders
    Route::get('/my-account', [AccountController::class, 'index'])->name('account.dashboard');
    Route::post('/my-account/update', [AccountController::class, 'updateProfile'])->name('account.update');
    Route::get('/my-orders', [AccountController::class, 'orders'])->name('account.orders');
    Route::get('/my-orders/{order_number}', [AccountController::class, 'orderDetail'])->name('account.order_detail');
    Route::get('/my-orders/{order_number}/invoice', [AccountController::class, 'downloadInvoice'])->name('account.invoice');
    Route::post('/product/review', [AccountController::class, 'submitReview'])->name('product.review');
});

// Admin Control Panel Routes
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;

Route::middleware(['auth', 'staff'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Products CRUD
    Route::resource('products', AdminProductController::class)->except(['update']);
    Route::post('/products/{id}/update', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/images/{imageId}', [AdminProductController::class, 'deleteImage'])->name('products.images.delete');

    // Categories CRUD
    Route::resource('categories', AdminCategoryController::class)->except(['update']);
    Route::post('/categories/{id}/update', [AdminCategoryController::class, 'update'])->name('categories.update');

    // Coupons CRUD
    Route::resource('coupons', AdminCouponController::class);

    // Orders Management
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::post('/orders/payments/{id}/verify', [AdminOrderController::class, 'verifyPayment'])->name('orders.payments.verify');

    // Settings & Banners CMS
    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');

    Route::get('/banners', [AdminSettingController::class, 'banners'])->name('banners.index');
    Route::get('/banners/create', [AdminSettingController::class, 'createBanner'])->name('banners.create');
    Route::post('/banners', [AdminSettingController::class, 'storeBanner'])->name('banners.store');
    Route::get('/banners/{id}/edit', [AdminSettingController::class, 'editBanner'])->name('banners.edit');
    Route::post('/banners/{id}/update', [AdminSettingController::class, 'updateBanner'])->name('banners.update');
    Route::delete('/banners/{id}', [AdminSettingController::class, 'destroyBanner'])->name('banners.destroy');
});

// Default Breeze Profile endpoints (unused but kept for compatibility)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/admin_auth.php';
require __DIR__.'/auth.php';
