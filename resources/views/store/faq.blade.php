@extends('layouts.store')

@section('title', 'Frequently Asked Questions | AUA Collection')

@section('content')
<section class="py-5 bg-white">
    <div class="container py-4">
        <!-- Header -->
        <div class="row justify-content-center text-center mb-5">
            <div class="col-lg-8">
                <span class="text-gold fw-semibold small text-uppercase letter-spacing-2 d-block mb-3">FAQ</span>
                <h1 class="font-serif fw-bold display-6 mb-3 text-dark letter-spacing-1">FREQUENTLY ASKED QUESTIONS</h1>
                <p class="text-muted small">Locate quick solutions to common queries regarding ordering, payment processing, and shipment status.</p>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion accordion-flush" id="faqAccordion">
                    
                    <!-- Item 1: Ordering -->
                    <div class="accordion-item border-bottom border-light py-2">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button collapsed font-serif fw-bold text-dark fs-6 bg-transparent shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                HOW DO I PLACE AN ORDER AT AUA COLLECTION?
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted small lh-lg">
                                Simply browse our Men, Women, or Kids collections. Open a product card, select your preferred size and color variants, select quantity, and click "Add to Cart". When ready, proceed to Checkout, register/login, input your shipping address, choose your payment option, and place the order.
                            </div>
                        </div>
                    </div>

                    <!-- Item 2: Manual Payment -->
                    <div class="accordion-item border-bottom border-light py-2">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed font-serif fw-bold text-dark fs-6 bg-transparent shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                HOW DOES MANUAL PAYMENT PROOF VERIFICATION WORK?
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted small lh-lg">
                                If you choose "Bank Transfer" or mobile payments (Easypaisa/JazzCash) during checkout, our bank account details will display on screen. Transfer the amount, take a screenshot of the confirmation, and upload it via the "My Account -> My Orders" page in your dashboard. An administrator will verify and approve the payment within 2 to 4 hours.
                            </div>
                        </div>
                    </div>

                    <!-- Item 3: Shipping -->
                    <div class="accordion-item border-bottom border-light py-2">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed font-serif fw-bold text-dark fs-6 bg-transparent shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                WHAT ARE THE SHIPPING CHARGES AND DELIVERY TIMELINES?
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted small lh-lg">
                                We charge a flat shipping rate of Rs. 250 across Pakistan. Standard delivery takes 3 to 5 business days once payment is verified. You can check the real-time shipping milestones (Packing, Ready to Ship, Shipped) on our "Track Order" page.
                            </div>
                        </div>
                    </div>

                    <!-- Item 4: Sizes -->
                    <div class="accordion-item border-bottom border-light py-2">
                        <h2 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed font-serif fw-bold text-dark fs-6 bg-transparent shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                HOW DO I CHOOSE THE RIGHT SIZE?
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted small lh-lg">
                                Since sizing standards vary by collections, we offer precise measurement specifications in the description tab of each garment. Standard sizes include Small, Medium, Large, and Extra Large. When in doubt, we recommend selecting your typical fit or contacting support.
                            </div>
                        </div>
                    </div>

                    <!-- Item 5: Returns -->
                    <div class="accordion-item border-0 py-2">
                        <h2 class="accordion-header" id="headingFive">
                            <button class="accordion-button collapsed font-serif fw-bold text-dark fs-6 bg-transparent shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                CAN I CANCEL OR EXCHANGE MY ORDER?
                            </button>
                        </h2>
                        <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted small lh-lg">
                                You can cancel an order within 1 hour of placing it directly from your dashboard, provided it hasn't advanced to "Packing". Exchanges for size/color alterations are supported within 7 days of delivery, provided tags remain attached and the product is unworn.
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection
