@extends('layouts.store')

@section('title', 'Contact Us | AUA Collection')

@section('content')
<section class="py-5 bg-white">
    <div class="container py-4">
        <!-- Header -->
        <div class="row justify-content-center text-center mb-5">
            <div class="col-lg-8">
                <span class="text-gold fw-semibold small text-uppercase letter-spacing-2 d-block mb-3">GET IN TOUCH</span>
                <h1 class="font-serif fw-bold display-6 mb-3 text-dark letter-spacing-1">WE WOULD LOVE TO HEAR FROM YOU</h1>
                <p class="text-muted small">Have queries regarding sizes, order verification status, or custom tailoring? Contact our customer care.</p>
            </div>
        </div>

        <div class="row g-5">
            <!-- Left Info Panel -->
            <div class="col-lg-5">
                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4 p-3 d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-2 fs-5 text-success"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                <div class="card border-0 shadow-sm rounded-4 p-5 bg-light h-100">
                    <h4 class="font-serif fw-bold text-uppercase fs-5 mb-4 text-dark">CONTACT DETAILS</h4>
                    
                    <div class="d-flex align-items-start gap-3 mb-4">
                        <div class="fs-4 text-gold"><i class="bi bi-geo-alt-fill"></i></div>
                        <div>
                            <span class="text-muted small d-block mb-1 text-uppercase fw-semibold">Head Office</span>
                            <span class="text-dark small">{{ $storeAddress }}</span>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3 mb-4">
                        <div class="fs-4 text-gold"><i class="bi bi-telephone-fill"></i></div>
                        <div>
                            <span class="text-muted small d-block mb-1 text-uppercase fw-semibold">Phone Support</span>
                            <span class="text-dark small">{{ $storePhone }}</span>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3 mb-4">
                        <div class="fs-4 text-gold"><i class="bi bi-envelope-fill"></i></div>
                        <div>
                            <span class="text-muted small d-block mb-1 text-uppercase fw-semibold">Email Inquiry</span>
                            <span class="text-dark small">{{ $storeEmail }}</span>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h4 class="font-serif fw-bold text-uppercase fs-5 mb-3 text-dark">BUSINESS HOURS</h4>
                    <ul class="list-unstyled text-muted small lh-lg mb-0">
                        <li class="d-flex justify-content-between"><span>Monday - Friday:</span> <strong class="text-dark">10:00 AM - 08:00 PM</strong></li>
                        <li class="d-flex justify-content-between"><span>Saturday:</span> <strong class="text-dark">11:00 AM - 06:00 PM</strong></li>
                        <li class="d-flex justify-content-between"><span>Sunday:</span> <strong class="text-dark">Closed</strong></li>
                    </ul>
                </div>
            </div>

            <!-- Right Contact Form -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 p-5 bg-white border border-light-subtle">
                    <h4 class="font-serif fw-bold text-uppercase fs-5 mb-4 text-dark">SEND A MESSAGE</h4>
                    <form action="{{ route('store.contact.submit') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label small fw-bold">Full Name *</label>
                            <input type="text" name="name" id="name" class="form-control form-control-luxury @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label small fw-bold">Email Address *</label>
                            <input type="email" name="email" id="email" class="form-control form-control-luxury @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="subject" class="form-label small fw-bold">Subject *</label>
                            <input type="text" name="subject" id="subject" class="form-control form-control-luxury @error('subject') is-invalid @enderror" value="{{ old('subject') }}" required>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="message" class="form-label small fw-bold">Your Message *</label>
                            <textarea name="message" id="message" rows="5" class="form-control form-control-luxury @error('message') is-invalid @enderror" required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-dark btn-luxury w-100 py-3 rounded-pill fw-semibold">SUBMIT MESSAGE</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
