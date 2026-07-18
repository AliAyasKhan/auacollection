@extends('layouts.store')

@section('title', 'Checkout - AUA Collection')

@section('content')

    <section class="py-5 bg-light">
        <div class="container">
            <h1 class="font-serif mb-5 fs-2 text-center">Secure Checkout</h1>

            @if(session('error'))
                <div class="alert alert-danger border-0 rounded-3 mb-4 text-center">{{ session('error') }}</div>
            @endif
            @if(session('success'))
                <div class="alert alert-success border-0 rounded-3 mb-4 text-center">{{ session('success') }}</div>
            @endif

            <form action="{{ route('checkout.place') }}" method="POST" enctype="multipart/form-data" id="checkoutForm">
                @csrf
                <input type="hidden" name="coupon_code" id="hidden_coupon_code" value="{{ $couponCode }}">

                <div class="row g-4">
                    
                    <!-- Left: Shipping & Payment Details -->
                    <div class="col-lg-8">
                        
                        <!-- Shipping Address -->
                        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                            <h4 class="font-heading fs-5 pb-2 mb-4 border-bottom border-light">1. SHIPPING ADDRESS</h4>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">First Name</label>
                                    <input type="text" name="first_name" class="form-control" value="{{ old('first_name', auth()->user()->name) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required placeholder="e.g. +92 300 1234567">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Delivery Address</label>
                                    <input type="text" name="address" class="form-control" value="{{ old('address') }}" required placeholder="Street address, apartment, house number, area">
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label">City</label>
                                    <input type="text" name="city" class="form-control" value="{{ old('city') }}" required placeholder="e.g. Lahore">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Postal / ZIP Code</label>
                                    <input type="text" name="postal_code" class="form-control" value="{{ old('postal_code') }}" required placeholder="e.g. 54000">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Country</label>
                                    <select name="country" class="form-select border-light-subtle rounded-3 py-2.5 px-3">
                                        <option value="Pakistan" selected>Pakistan</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Order Notes (Optional)</label>
                                    <textarea name="notes" rows="3" class="form-control" placeholder="Special delivery instructions, landmarks, etc.">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Methods -->
                        <div class="card border-0 shadow-sm rounded-4 p-4">
                            <h4 class="font-heading fs-5 pb-2 mb-4 border-bottom border-light">2. PAYMENT METHOD</h4>
                            
                            <div class="mb-4">
                                <!-- Cash On Delivery -->
                                <div class="form-check p-3 border rounded-3 mb-3 hover-shadow-sm">
                                    <input class="form-check-input ms-0 me-3" type="radio" name="payment_method" id="pay-cod" value="COD" {{ old('payment_method', 'COD') === 'COD' ? 'checked' : '' }} style="accent-color: var(--color-primary-dark)">
                                    <label class="form-check-label fw-bold text-dark" for="pay-cod">
                                        Cash On Delivery (COD)
                                        <span class="d-block small text-muted fw-normal mt-1">Pay with cash upon arrival at your doorstep. (Pakistan only)</span>
                                    </label>
                                </div>

                                <!-- Bank Transfer -->
                                <div class="form-check p-3 border rounded-3 mb-3 hover-shadow-sm">
                                    <input class="form-check-input ms-0 me-3" type="radio" name="payment_method" id="pay-bank" value="Bank Transfer" {{ old('payment_method') === 'Bank Transfer' ? 'checked' : '' }} style="accent-color: var(--color-primary-dark)">
                                    <label class="form-check-label fw-bold text-dark" for="pay-bank">
                                        Direct Bank Transfer (Manual Verification)
                                        <span class="d-block small text-muted fw-normal mt-1">Transfer funds directly to our bank account. We will verify and process the order.</span>
                                    </label>
                                    
                                    <!-- Bank Details Block -->
                                    <div class="mt-3 p-3 bg-light rounded-3 d-none border-start border-3 border-dark" id="bank-details">
                                        <h6 class="fs-7 fw-bold text-dark mb-2">OUR BANK DETAILS:</h6>
                                        <ul class="list-unstyled small text-muted mb-0 lh-lg">
                                            <li><strong>Bank:</strong> Habib Bank Limited (HBL)</li>
                                            <li><strong>Account Title:</strong> AUA Collection Ltd.</li>
                                            <li><strong>Account Number:</strong> 1234-5678-9012-34</li>
                                            <li><strong>IBAN:</strong> PK99 HABB 1234 5678 9012 3400</li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Easypaisa / JazzCash -->
                                <div class="form-check p-3 border rounded-3 mb-3 hover-shadow-sm">
                                    <input class="form-check-input ms-0 me-3" type="radio" name="payment_method" id="pay-easypaisa" value="Easypaisa" {{ old('payment_method') === 'Easypaisa' ? 'checked' : '' }} style="accent-color: var(--color-primary-dark)">
                                    <label class="form-check-label fw-bold text-dark" for="pay-easypaisa">
                                        Easypaisa / JazzCash (Manual Verification)
                                        <span class="d-block small text-muted fw-normal mt-1">Send funds directly to our mobile wallet.</span>
                                    </label>
                                    
                                    <!-- Mobile Wallet details -->
                                    <div class="mt-3 p-3 bg-light rounded-3 d-none border-start border-3 border-dark" id="wallet-details">
                                        <h6 class="fs-7 fw-bold text-dark mb-2">MOBILE WALLET DETAILS:</h6>
                                        <ul class="list-unstyled small text-muted mb-0 lh-lg">
                                            <li><strong>Easypaisa Account:</strong> 0300-1234567 (Title: AUA Collection)</li>
                                            <li><strong>JazzCash Account:</strong> 0300-9876543 (Title: AUA Collection)</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Proof Image Upload -->
                            <div id="proof-upload-section" class="d-none bg-light p-4 rounded-3 border">
                                <label class="form-label fw-bold text-dark mb-1">UPLOAD PAYMENT SCREENSHOT</label>
                                <span class="d-block small text-muted mb-3">Please upload a JPEG/PNG screenshot showing the transaction ID and sent amount.</span>
                                <input type="file" name="proof_image" id="proof_image" class="form-control bg-white">
                                @error('proof_image')
                                    <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <!-- Right: Sticky Order Summary & Coupon -->
                    <div class="col-lg-4">
                        
                        <!-- Coupon box -->
                        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                            <label class="form-label small fw-bold">PROMOTIONAL CODE</label>
                            <div class="input-group">
                                <input type="text" id="coupon_input" class="form-control bg-light border-0 shadow-none py-2" placeholder="Enter coupon..." value="{{ $couponCode }}">
                                <button type="button" class="btn btn-dark border-0 px-3" id="btn-apply-coupon">APPLY</button>
                            </div>
                            <span class="text-success small mt-2 d-none" id="coupon-msg"></span>
                        </div>

                        <!-- Sticky summary details -->
                        <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 100px; z-index: 1;">
                            <h4 class="font-heading fs-5 pb-2 mb-4 border-bottom border-light">YOUR ORDER</h4>
                            
                            <!-- Items list summary -->
                            <div class="mb-4">
                                @foreach($cart->items as $item)
                                    <div class="d-flex justify-content-between mb-2 small text-muted">
                                        <span class="text-truncate" style="max-width: 200px;">{{ $item->product->name }} x {{ $item->quantity }}</span>
                                        @php
                                            $itemPrice = $item->product->active_price;
                                            if ($item->variant) {
                                                $itemPrice += $item->variant->additional_price;
                                            }
                                        @endphp
                                        <span>{{ $storeCurrencySymbol }}{{ number_format($itemPrice * $item->quantity) }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <div style="border-top: 1px solid var(--color-gray-200); margin-bottom: 1.5rem;"></div>

                            <!-- Calculations -->
                            <div class="d-flex justify-content-between mb-3 text-muted">
                                <span class="small">Subtotal</span>
                                <span>{{ $storeCurrencySymbol }}{{ number_format($summary['subtotal']) }}</span>
                            </div>
                            
                            <div class="d-flex justify-content-between mb-3 text-success d-none" id="summary-discount-row">
                                <span class="small">Coupon Discount</span>
                                <span>-{{ $storeCurrencySymbol }}<span id="summary-discount">{{ number_format($summary['discount']) }}</span></span>
                            </div>

                            <div class="d-flex justify-content-between mb-3 text-muted">
                                <span class="small">Shipping Charges</span>
                                <span>{{ $storeCurrencySymbol }}{{ number_format($summary['shipping_charges']) }}</span>
                            </div>

                            <div class="d-flex justify-content-between mb-4 text-muted">
                                <span class="small">GST / Tax ({{ $summary['tax_percentage'] }}%)</span>
                                <span>{{ $storeCurrencySymbol }}<span id="summary-tax">{{ number_format($summary['tax']) }}</span></span>
                            </div>

                            <div style="border-top: 1px dashed var(--color-gray-200); margin-bottom: 1.5rem;"></div>

                            <div class="d-flex justify-content-between mb-4">
                                <span class="fw-bold">Total Payable</span>
                                <span class="fw-bold fs-5 text-dark">{{ $storeCurrencySymbol }}<span id="summary-total">{{ number_format($summary['total']) }}</span></span>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn-luxury-dark py-3" id="btn-place-order">PLACE ORDER</button>
                            </div>
                        </div>

                    </div>

                </div>
            </form>
        </div>
    </section>

@endsection

@push('scripts')
<script>
    const radioCOD = document.getElementById('pay-cod');
    const radioBank = document.getElementById('pay-bank');
    const radioEasypaisa = document.getElementById('pay-easypaisa');

    const bankDetails = document.getElementById('bank-details');
    const walletDetails = document.getElementById('wallet-details');
    const proofUpload = document.getElementById('proof-upload-section');
    const proofInput = document.getElementById('proof_image');

    // Display discount dynamically if coupon exists
    const discountVal = {{ $summary['discount'] }};
    if (discountVal > 0) {
        document.getElementById('summary-discount-row').classList.remove('d-none');
    }

    // Toggle manual payment info & uploads fields
    function handlePaymentMethodChange() {
        if (radioCOD.checked) {
            bankDetails.classList.add('d-none');
            walletDetails.classList.add('d-none');
            proofUpload.classList.add('d-none');
            proofInput.required = false;
        } else if (radioBank.checked) {
            bankDetails.classList.remove('d-none');
            walletDetails.classList.add('d-none');
            proofUpload.classList.remove('d-none');
            proofInput.required = true;
        } else if (radioEasypaisa.checked) {
            bankDetails.classList.add('d-none');
            walletDetails.classList.remove('d-none');
            proofUpload.classList.remove('d-none');
            proofInput.required = true;
        }
    }

    [radioCOD, radioBank, radioEasypaisa].forEach(radio => {
        radio.addEventListener('change', handlePaymentMethodChange);
    });

    // Run once on load
    handlePaymentMethodChange();

    // AJAX Coupon Applier
    document.getElementById('btn-apply-coupon').addEventListener('click', function() {
        const coupon = document.getElementById('coupon_input').value.trim();
        const msgSpan = document.getElementById('coupon-msg');

        if (!coupon) return;

        fetch("{{ route('checkout.coupon') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            },
            body: JSON.stringify({ coupon_code: coupon })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                msgSpan.className = "text-success small mt-2 d-block";
                msgSpan.innerText = data.message;
                
                document.getElementById('hidden_coupon_code').value = coupon;

                document.getElementById('summary-discount').innerText = parseFloat(data.summary.discount).toLocaleString();
                document.getElementById('summary-tax').innerText = parseFloat(data.summary.tax).toLocaleString();
                document.getElementById('summary-total').innerText = parseFloat(data.summary.total).toLocaleString();
                document.getElementById('summary-discount-row').classList.remove('d-none');
            } else {
                msgSpan.className = "text-danger small mt-2 d-block";
                msgSpan.innerText = data.message;
                document.getElementById('hidden_coupon_code').value = "";
                document.getElementById('summary-discount-row').classList.add('d-none');
                
                document.getElementById('summary-discount').innerText = "0";
                document.getElementById('summary-tax').innerText = parseFloat({{ $summary['tax'] }}).toLocaleString();
                document.getElementById('summary-total').innerText = parseFloat({{ $summary['total'] }}).toLocaleString();
            }
        })
        .catch(err => {
            console.error(err);
        });
    });
</script>
@endpush
