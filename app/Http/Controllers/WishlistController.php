<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlist = Wishlist::with(['product.primaryImage', 'product.category', 'product.brand'])
            ->where('user_id', Auth::id())
            ->get();
            
        return view('store.wishlist', compact('wishlist'));
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $userId = Auth::id();
        $productId = $request->product_id;

        $existing = Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            $existing->delete();
            $status = 'removed';
            $message = 'Item removed from wishlist.';
        } else {
            Wishlist::create([
                'user_id' => $userId,
                'product_id' => $productId,
            ]);
            $status = 'added';
            $message = 'Item added to wishlist.';
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'status' => $status,
                'message' => $message,
                'count' => Wishlist::where('user_id', $userId)->count()
            ]);
        }

        return back()->with('success', $message);
    }
}
