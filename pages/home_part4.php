<!-- ***** Features Section ***** -->
<section id="features" class="container py-5 my-5">
    <h2 class="section-title">Our Services</h2>
    <p class="section-subtitle">Experience the difference with our premium services</p>

    <div class="row g-4">
        <div class="col-lg-4 col-md-6">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-shipping-fast"></i>
                </div>
                <h4 class="fw-bold">Complimentary Shipping</h4>
                <p class="text-muted">Free express shipping on all orders over $200. Delivered directly to your doorstep with care.</p>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-undo"></i>
                </div>
                <h4 class="fw-bold">Hassle-Free Returns</h4>
                <p class="text-muted">30-day return policy for all items. Your satisfaction is our priority.</p>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <h4 class="fw-bold">Secure Transactions</h4>
                <p class="text-muted">Your payment information is protected with enterprise-grade encryption technology.</p>
            </div>
        </div>
    </div>
</section>

<!-- ***** Newsletter Section ***** -->
<section class="newsletter-section">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h2 class="fw-bold mb-3">Stay Informed</h2>
                <p class="mb-4 opacity-90">Subscribe to our newsletter for exclusive offers and new collection previews</p>
                <form id="newsletter-form" method="POST" class="row g-3 justify-content-center">
                    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                    <div class="col-lg-8">
                        <input type="email" name="email" class="form-control form-control-lg" placeholder="Enter your email address" required>
                    </div>
                    <div class="col-lg-4">
                        <button class="btn btn-light btn-lg w-100" type="submit">Subscribe</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
