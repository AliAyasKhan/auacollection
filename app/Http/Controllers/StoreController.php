<?php

namespace App\Http\Controllers;

use App\Repositories\ProductRepositoryInterface;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Brand;
use App\Models\Setting;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function home()
    {
        $banners = Banner::where('status', true)->orderBy('order', 'ASC')->get();
        $categories = Category::where('status', true)
            ->with(['products' => function ($q) {
                $q->where('status', true)->with('primaryImage')->latest()->limit(1);
            }])
            ->orderBy('name')
            ->limit(6)
            ->get();
        $featured = $this->productRepository->getFeatured(8);
        $newArrivals = $this->productRepository->getNewArrivals(8);
        $saleProducts = $this->productRepository->getSaleProducts(8);
        $collections = Collection::where('status', true)->limit(3)->get();

        return view('store.home', compact('banners', 'categories', 'featured', 'newArrivals', 'saleProducts', 'collections'));
    }

    public function shop(Request $request)
    {
        $filters = $request->only([
            'category', 'collection', 'brand', 'price_min', 
            'price_max', 'colors', 'sizes', 'search', 'sort_by'
        ]);

        // Support simple query triggers like new_arrival=1 or sale=1
        if ($request->filled('new_arrival')) {
            $filters['new_arrival'] = true;
        }

        if ($request->filled('sale')) {
            $filters['sale'] = true;
        }

        $products = $this->productRepository->searchAndFilter($filters, 12);
        
        $categories = $this->productRepository->getAllCategories();
        $collections = $this->productRepository->getAllCollections();
        $brands = $this->productRepository->getAllBrands();
        $colors = $this->productRepository->getAllColors();
        $sizes = $this->productRepository->getAllSizes();

        return view('store.shop', compact('products', 'categories', 'collections', 'brands', 'colors', 'sizes'));
    }

    public function detail($slug)
    {
        $product = $this->productRepository->findBySlug($slug);
        
        // Fetch related products (in same category)
        $relatedProducts = \App\Models\Product::with(['primaryImage', 'category', 'brand'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', true)
            ->limit(4)
            ->get();

        return view('store.detail', compact('product', 'relatedProducts'));
    }

    public function about()
    {
        return view('store.about');
    }

    public function contact()
    {
        return view('store.contact');
    }

    public function handleContactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // In production, send mail or store contact request.
        // For now, redirect back with success.
        return back()->with('success', 'Thank you for contacting us. Our team will get back to you shortly.');
    }

    public function privacyPolicy()
    {
        return view('store.privacy');
    }

    public function termsConditions()
    {
        return view('store.terms');
    }

    public function faq()
    {
        return view('store.faq');
    }
}
