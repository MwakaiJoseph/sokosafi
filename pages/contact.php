<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="mb-4 text-center fw-bold">Contact Us</h1>
            <p class="lead text-center mb-5 text-muted">We'd love to hear from you. Please send us a message or contact us via the details below.</p>
            
            <div class="row mb-5">
                <div class="col-md-4 text-center mb-4 mb-md-0">
                    <div class="h-100 p-4 border rounded shadow-sm">
                        <i class="fas fa-map-marker-alt fa-2x text-primary mb-3"></i>
                        <h5 class="fw-bold">Visit Us</h5>
                        <p class="text-muted">Nairobi, Kenya</p>
                    </div>
                </div>
                <div class="col-md-4 text-center mb-4 mb-md-0">
                    <div class="h-100 p-4 border rounded shadow-sm">
                        <i class="fas fa-phone fa-2x text-primary mb-3"></i>
                        <h5 class="fw-bold">Call Us</h5>
                        <p class="text-muted">0758549123</p>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="h-100 p-4 border rounded shadow-sm">
                        <i class="fas fa-envelope fa-2x text-primary mb-3"></i>
                        <h5 class="fw-bold">Email Us</h5>
                        <p class="text-muted">support@synora.dev</p>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">
                    <h3 class="mb-4">Send us a Message</h3>
                    
                    <?php if (isset($_SESSION['contact_success'])): ?>
                        <div class="alert alert-success">
                            <?php echo htmlspecialchars($_SESSION['contact_success']);
    unset($_SESSION['contact_success']); ?>
                        </div>
                    <?php
endif; ?>
                    
                    <?php if (isset($_SESSION['contact_error'])): ?>
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($_SESSION['contact_error']);
    unset($_SESSION['contact_error']); ?>
                        </div>
                    <?php
endif; ?>

                    <form method="post" action="index.php?page=contact">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                        <input type="hidden" name="action" value="submit_contact">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Your Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="John Doe" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Your Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="john@example.com" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="subject" name="subject" placeholder="How can we help?" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="5" placeholder="Write your message here..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary px-4">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
