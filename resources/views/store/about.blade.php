@extends('layouts.store')

@section('title', 'About Us | AUA Collection - Luxury Fashion Brand')

@section('content')
<section class="py-5 bg-white">
    <div class="container py-4">
        <!-- Hero Section -->
        <div class="row justify-content-center text-center mb-5">
            <div class="col-lg-8">
                <span class="text-gold fw-semibold small text-uppercase letter-spacing-2 d-block mb-3">OUR HERITAGE</span>
                <h1 class="font-serif fw-bold display-5 mb-4 text-dark letter-spacing-1">DESIGNING COMFORT FOR THE MODERN ELITE</h1>
                <p class="lead text-muted lh-lg fs-6">
                    AUA Collection is a premium fashion house founded on the principles of minimal design, meticulous tailoring, and uncompromising quality. We create clothing that moves with you, blending luxury aesthetics with everyday function.
                </p>
            </div>
        </div>

        <!-- Vision, Mission and Craft Cards -->
        <div class="row g-4 py-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-5 h-100 bg-light text-center">
                    <div class="fs-1 text-gold mb-3"><i class="bi bi-gem"></i></div>
                    <h4 class="font-serif fw-bold text-uppercase fs-5 mb-3">Sartorial Quality</h4>
                    <p class="text-muted small lh-lg mb-0">
                        Every thread, button, and seam is carefully selected and constructed. Our pieces undergo rigorous craftsmanship standards to ensure lifetime durability and pure feel.
                    </p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-5 h-100 bg-light text-center">
                    <div class="fs-1 text-dark mb-3"><i class="bi bi-eye"></i></div>
                    <h4 class="font-serif fw-bold text-uppercase fs-5 mb-3">Minimal Vision</h4>
                    <p class="text-muted small lh-lg mb-0">
                        Inspired by clean Apple-esque industrial styles, our garments emphasize silhouettes and premium raw materials over loud logos, creating quiet luxury.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-5 h-100 bg-light text-center">
                    <div class="fs-1 text-dark mb-3"><i class="bi bi-flower1"></i></div>
                    <h4 class="font-serif fw-bold text-uppercase fs-5 mb-3">Organic Comfort</h4>
                    <p class="text-muted small lh-lg mb-0">
                        We prioritize organic cotton, premium merino wools, and ethical silks, ensuring our apparel remains extremely breathable, soft, and comfortable all day.
                    </p>
                </div>
            </div>
        </div>

        <!-- Story Section -->
        <div class="row align-items-center mt-5 pt-4 g-5">
            <div class="col-lg-6">
                <img src="{{ asset('assets/images/placeholder.jpg') }}" alt="Our Boutique Design Studio" class="img-fluid rounded-4 shadow-sm w-100" style="max-height: 450px; object-fit: cover;">
            </div>
            <div class="col-lg-6">
                <h3 class="font-serif fw-bold text-uppercase letter-spacing-1 mb-4 text-dark">The AUA Craftsmanship Legacy</h3>
                <p class="text-muted lh-lg mb-3">
                    Established with a desire to redefine luxury loungewear and formal apparel, AUA Collection represents a perfect synergy between traditional tailoring methods and forward-thinking garment engineering.
                </p>
                <p class="text-muted lh-lg mb-4">
                    Whether you are browsing our Men’s collections, Women’s silk ensembles, or Kids’ premium organic wear, our garments are crafted to tell a story of understated elegance and high status.
                </p>
                <a href="{{ url('/shop') }}" class="btn btn-dark btn-luxury px-5 py-3 rounded-pill fw-semibold">EXPLORE THE SHOP</a>
            </div>
        </div>
    </div>
</section>
@endsection
