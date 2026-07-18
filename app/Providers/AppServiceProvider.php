<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\ProductRepositoryInterface;
use App\Repositories\ProductRepository;
use App\Repositories\CartRepositoryInterface;
use App\Repositories\CartRepository;
use App\Repositories\OrderRepositoryInterface;
use App\Repositories\OrderRepository;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(CartRepositoryInterface::class, CartRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        ResetPassword::createUrlUsing(function ($user, string $token) {
            $email = $user->getEmailForPasswordReset();

            if (method_exists($user, 'isStaff') && $user->isStaff()) {
                return url(route('admin.password.reset', [
                    'token' => $token,
                    'email' => $email,
                ], false));
            }

            return url(route('password.reset', [
                'token' => $token,
                'email' => $email,
            ], false));
        });

        view()->composer('*', function ($view) {
            $cartCount = 0;
            $wishlistCount = 0;

            try {
                if (auth()->check()) {
                    $cartCount = \App\Models\CartItem::whereHas('cart', function($q) {
                        $q->where('user_id', auth()->id());
                    })->sum('quantity');

                    $wishlistCount = \App\Models\Wishlist::where('user_id', auth()->id())->count();
                } else {
                    $sessionId = session()->get('cart_session_id');
                    if ($sessionId) {
                        $cartCount = \App\Models\CartItem::whereHas('cart', function($q) use ($sessionId) {
                            $q->where('session_id', $sessionId);
                        })->sum('quantity');
                    }
                }
            } catch (\Exception $e) {
                // Prevent migration block errors
            }

            $view->with([
                'globalCartCount' => $cartCount,
                'globalWishlistCount' => $wishlistCount,
                'storeName' => \App\Models\Setting::get('store_name', 'AUA Collection'),
                'storePhone' => \App\Models\Setting::get('store_phone', '+92 300 1234567'),
                'storeEmail' => \App\Models\Setting::get('store_email', 'info@auacollection.com'),
                'storeAddress' => \App\Models\Setting::get('store_address', 'Lahore, Pakistan'),
                'storeCurrencySymbol' => \App\Models\Setting::get('currency_symbol', 'Rs.'),
                'storeShippingCharges' => \App\Models\Setting::get('shipping_charges', '250.00'),
                'storeTaxPercentage' => \App\Models\Setting::get('tax_percentage', '5.00'),
            ]);
        });
    }
}
