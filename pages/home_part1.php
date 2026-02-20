<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/db_functions.php';

// Improved error handling
try {
    $items = db_has_connection() ? get_products(12) : [];
}
catch (Exception $e) {
    error_log("Database error: " . $e->getMessage());
    $items = [];
}
// Use shared format_currency from includes/db_functions.php
?>

<!-- Loading Indicator -->
<div id="loading-indicator" class="text-center py-5" style="display:none;">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<!-- ***** Hero Section Start ***** -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero-content">
                    <span class="hero-badge">New Collection</span>
                    <h1 class="hero-title">Timeless Elegance, Modern Sophistication</h1>
                    <p class="hero-description">Discover our curated collection of premium products designed for the discerning individual. Experience unparalleled quality and craftsmanship.</p>
                    <a href="index.php?page=products" class="hero-btn">
                        Explore Collection <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" 
                     alt="Premium Collection" 
                     class="img-fluid text-center w-100"
                     loading="lazy"
                     style="border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
            </div>
        </div>
    </div>
</section>
<!-- ***** Hero Section End ***** -->

<!-- ***** Featured Products Section ***** -->
<section class="container py-5 my-5">
    <h2 class="section-title">Curated Selection</h2>
    <p class="section-subtitle">Discover our carefully curated collection of premium products</p>

    <div class="row g-4">
