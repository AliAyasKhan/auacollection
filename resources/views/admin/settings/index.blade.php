@extends('layouts.admin')

@section('title', 'Store Settings - AUA Collection')

@section('content')
<div class="mb-4">
    <h4 class="font-serif fw-bold text-uppercase letter-spacing-1">Global Store Settings</h4>
    <p class="text-muted small mb-0">Configure business information, shipping parameters, tax rates, social links, and SEO tags.</p>
</div>

<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row">
        <!-- Left: Store Profile & Finances -->
        <div class="col-lg-8">
            <!-- Business Profile -->
            <div class="card card-luxury p-4">
                <h5 class="font-serif mb-4 fs-6 fw-bold text-uppercase border-bottom pb-2">Business Details</h5>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="store_name" class="form-label small fw-bold">Store Name *</label>
                        <input type="text" name="store_name" id="store_name" class="form-control" value="{{ $settings['store_name'] ?? '' }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="store_email" class="form-label small fw-bold">Contact Email *</label>
                        <input type="email" name="store_email" id="store_email" class="form-control" value="{{ $settings['store_email'] ?? '' }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="store_phone" class="form-label small fw-bold">Contact Phone *</label>
                        <input type="text" name="store_phone" id="store_phone" class="form-control" value="{{ $settings['store_phone'] ?? '' }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="whatsapp_number" class="form-label small fw-bold">WhatsApp Number</label>
                        <input type="text" name="whatsapp_number" id="whatsapp_number" class="form-control" value="{{ $settings['whatsapp_number'] ?? '' }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="store_address" class="form-label small fw-bold">Store Address</label>
                    <textarea name="store_address" id="store_address" rows="3" class="form-control">{{ $settings['store_address'] ?? '' }}</textarea>
                </div>
            </div>

            <!-- Tax & Shipping Config -->
            <div class="card card-luxury p-4">
                <h5 class="font-serif mb-4 fs-6 fw-bold text-uppercase border-bottom pb-2">Logistics & Finances</h5>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="shipping_charges" class="form-label small fw-bold">Flat Shipping Charges (Rs.) *</label>
                        <input type="number" step="0.01" name="shipping_charges" id="shipping_charges" class="form-control" value="{{ $settings['shipping_charges'] ?? '0.00' }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="tax_percentage" class="form-label small fw-bold">Government Tax Percentage (%) *</label>
                        <input type="number" step="0.01" name="tax_percentage" id="tax_percentage" class="form-control" value="{{ $settings['tax_percentage'] ?? '0.00' }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <label for="currency" class="form-label small fw-bold">Currency *</label>
                        <input type="text" name="currency" id="currency" class="form-control" value="{{ $settings['currency'] ?? '' }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="currency_symbol" class="form-label small fw-bold">Currency Symbol *</label>
                        <input type="text" name="currency_symbol" id="currency_symbol" class="form-control" value="{{ $settings['currency_symbol'] ?? '' }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="timezone" class="form-label small fw-bold">Timezone *</label>
                        <input type="text" name="timezone" id="timezone" class="form-control" value="{{ $settings['timezone'] ?? '' }}" required>
                    </div>
                </div>
            </div>

            <!-- SEO Controls -->
            <div class="card card-luxury p-4">
                <h5 class="font-serif mb-4 fs-6 fw-bold text-uppercase border-bottom pb-2">SEO Meta Data</h5>
                
                <div class="mb-3">
                    <label for="seo_meta_title" class="form-label small fw-bold">SEO Default Page Title</label>
                    <input type="text" name="seo_meta_title" id="seo_meta_title" class="form-control" value="{{ $settings['seo_meta_title'] ?? '' }}">
                </div>

                <div class="mb-3">
                    <label for="seo_meta_description" class="form-label small fw-bold">SEO Default Page Description</label>
                    <textarea name="seo_meta_description" id="seo_meta_description" rows="4" class="form-control">{{ $settings['seo_meta_description'] ?? '' }}</textarea>
                </div>
            </div>
        </div>

        <!-- Right: Social Links & Submit -->
        <div class="col-lg-4">
            <!-- Social Networks Links -->
            <div class="card card-luxury p-4">
                <h5 class="font-serif mb-4 fs-6 fw-bold text-uppercase border-bottom pb-2">Social Channels</h5>
                
                <div class="mb-3">
                    <label for="facebook_link" class="form-label small fw-bold"><i class="bi bi-facebook text-primary"></i> Facebook Page URL</label>
                    <input type="url" name="facebook_link" id="facebook_link" class="form-control" value="{{ $settings['facebook_link'] ?? '' }}">
                </div>

                <div class="mb-3">
                    <label for="instagram_link" class="form-label small fw-bold"><i class="bi bi-instagram text-danger"></i> Instagram Username URL</label>
                    <input type="url" name="instagram_link" id="instagram_link" class="form-control" value="{{ $settings['instagram_link'] ?? '' }}">
                </div>

                <div class="mb-3">
                    <label for="tiktok_link" class="form-label small fw-bold"><i class="bi bi-tiktok text-dark"></i> TikTok Channel URL</label>
                    <input type="url" name="tiktok_link" id="tiktok_link" class="form-control" value="{{ $settings['tiktok_link'] ?? '' }}">
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card card-luxury p-4">
                <button type="submit" class="btn btn-luxury-gold w-100 py-3 fw-bold mb-2">SAVE ALL SETTINGS</button>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-dark w-100 py-3">CANCEL</a>
            </div>
        </div>
    </div>
</form>
@endsection
