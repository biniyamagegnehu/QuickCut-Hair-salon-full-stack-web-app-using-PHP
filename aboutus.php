<?php
session_start();
require_once 'includes/db.php';
$is_logged_in = isset($_SESSION['user_id']);

// Fetch About Content
try {
    $stmt = $pdo->query("SELECT * FROM about_content LIMIT 1");
    $about_content = $stmt->fetch();
} catch (PDOException $e) {
    $about_content = null;
}
?>
<?php include 'includes/header.php'; ?>
    <!-- Custom About Styles -->
    <link rel="stylesheet" href="assets/css/aboutus.css">

    <!-- Main Content -->
    <main class="container mt-5 pt-5" id="about-main">
        <!-- Page Header -->
        <div class="page-header text-center mb-5" id="page-header">
            <h1 class="display-4 fw-bold" id="page-title"><?php echo htmlspecialchars($about_content['about_title'] ?? 'About QuickCut'); ?></h1>
            <p class="lead text-muted" id="page-subtitle"><?php echo htmlspecialchars($about_content['about_description'] ?? 'Your premier destination for modern men\'s grooming'); ?></p>
            <div class="header-divider" id="header-divider">
                <i class="fas fa-scissors"></i>
            </div>
        </div>

        <div class="row">
            <!-- Left Column - Our Story -->
            <div class="col-lg-8 mb-5 mb-lg-0" id="left-column">
                <!-- Our Story Section -->
                <div class="card login-card shadow-lg mb-5" id="our-story-card">
                    <div class="card-body p-4 p-md-5">
                        <h2 class="fw-bold mb-4 section-title" id="our-story-title">
                            <i class="fas fa-history me-2 text-primary"></i>Our Story
                        </h2>
                        <div class="our-story-content" id="our-story-content">
                            <p class="mb-4">
                                <?php echo nl2br(htmlspecialchars($about_content['story'] ?? 'QuickCut was founded with a simple mission...')); ?>
                            </p>
                        </div>
                        
                        <?php if (!empty($about_content['mission'])): ?>
                        <div class="mission-vision mt-5">
                            <h3 class="fw-bold mb-3"><i class="fas fa-bullseye me-2 text-primary"></i>Our Mission</h3>
                            <p><?php echo nl2br(htmlspecialchars($about_content['mission'])); ?></p>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($about_content['vision'])): ?>
                        <div class="mission-vision mt-4">
                            <h3 class="fw-bold mb-3"><i class="fas fa-eye me-2 text-primary"></i>Our Vision</h3>
                            <p><?php echo nl2br(htmlspecialchars($about_content['vision'])); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Why Choose Us Section -->
                <div class="card login-card shadow-lg" id="why-choose-us-card">
                    <div class="card-body p-4 p-md-5">
                        <h2 class="fw-bold mb-4 section-title" id="why-choose-us-title">
                            <i class="fas fa-star me-2 text-primary"></i>Why Choose Us
                        </h2>
                        <div class="row g-4" id="features-grid">
                            <div class="col-md-6">
                                <div class="feature-item" id="feature-1">
                                    <div class="feature-icon mb-3">
                                        <i class="fas fa-user-tie fa-2x"></i>
                                    </div>
                                    <h5 class="fw-bold" id="feature-1-title">Expert Barbers</h5>
                                    <p class="text-muted small" id="feature-1-desc">Professional barbers with 10+ years experience</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="feature-item" id="feature-2">
                                    <div class="feature-icon mb-3">
                                        <i class="fas fa-spa fa-2x"></i>
                                    </div>
                                    <h5 class="fw-bold" id="feature-2-title">Premium Products</h5>
                                    <p class="text-muted small" id="feature-2-desc">High-quality grooming products for best results</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="feature-item" id="feature-3">
                                    <div class="feature-icon mb-3">
                                        <i class="fas fa-chart-line fa-2x"></i>
                                    </div>
                                    <h5 class="fw-bold" id="feature-3-title">Real-time Updates</h5>
                                    <p class="text-muted small" id="feature-3-desc">Live queue status and wait time tracking</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="feature-item" id="feature-4">
                                    <div class="feature-icon mb-3">
                                        <i class="fas fa-calendar-check fa-2x"></i>
                                    </div>
                                    <h5 class="fw-bold" id="feature-4-title">Online Booking</h5>
                                    <p class="text-muted small" id="feature-4-desc">Convenient appointment scheduling online</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="feature-item" id="feature-5">
                                    <div class="feature-icon mb-3">
                                        <i class="fas fa-store fa-2x"></i>
                                    </div>
                                    <h5 class="fw-bold" id="feature-5-title">Modern Facilities</h5>
                                    <p class="text-muted small" id="feature-5-desc">Clean, comfortable, and stylish environment</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="feature-item" id="feature-6">
                                    <div class="feature-icon mb-3">
                                        <i class="fas fa-tag fa-2x"></i>
                                    </div>
                                    <h5 class="fw-bold" id="feature-6-title">Competitive Pricing</h5>
                                    <p class="text-muted small" id="feature-6-desc">Affordable prices for premium services</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Contact & Location -->
            <div class="col-lg-4" id="right-column">
                <!-- Location Card -->
                <div class="card login-card shadow-lg mb-4" id="location-card">
                    <div class="card-body p-4">
                        <h3 class="fw-bold mb-4 section-title" id="location-title">
                            <i class="fas fa-map-marker-alt me-2 text-primary"></i>Find Us Here
                        </h3>
                        
                        <div class="location-info mb-4" id="location-info">
                            <p class="mb-3" id="location-address">
                                <i class="fas fa-map-pin me-2 text-primary"></i>
                                <strong id="address-text"><?php echo htmlspecialchars($salon_settings['address'] ?? '123 Main Street, Downtown'); ?></strong>
                            </p>
                            <a href="<?php echo htmlspecialchars($salon_settings['map_link'] ?? 'https://www.google.com/maps?q=' . urlencode($salon_settings['address'] ?? '123 Main Street, Downtown')); ?>" 
                                class="btn btn-outline-primary btn-lg w-100 mb-3"
                                target="_blank" rel="noopener noreferrer" id="google-maps-btn">
                                 <i class="fas fa-map-marked-alt me-2"></i>Open in Google Maps
                            </a>
                            <div class="location-preview" id="location-preview">
                                <?php if (!empty($about_content['about_image'])): ?>
                                    <img src="<?php echo htmlspecialchars($about_content['about_image']); ?>" alt="Salon" class="img-fluid rounded">
                                <?php else: ?>
                                    <div class="map-placeholder" id="map-placeholder">
                                        <i class="fas fa-map fa-3x text-muted"></i>
                                        <p class="small mt-2">Location Preview</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information Card -->
                <div class="card login-card shadow-lg mb-4" id="contact-card">
                    <div class="card-body p-4">
                        <h3 class="fw-bold mb-4 section-title" id="contact-title">
                            <i class="fas fa-address-card me-2 text-primary"></i>Contact Information
                        </h3>
                        
                        <div class="contact-details mb-4" id="contact-details">
                            <div class="contact-item mb-3" id="contact-location">
                                <h5 class="fw-bold mb-2">
                                    <i class="fas fa-map-marker-alt me-2 text-primary"></i>Location
                                </h5>
                                <p class="mb-1"><?php echo htmlspecialchars($salon_settings['address'] ?? '123 Main Street, Downtown'); ?></p>
                                <a href="<?php echo htmlspecialchars($salon_settings['map_link'] ?? 'https://www.google.com/maps?q=' . urlencode($salon_settings['address'] ?? '123 Main Street, Downtown')); ?>" 
                                   class="text-primary small"
                                   target="_blank" rel="noopener noreferrer" id="view-maps-link">
                                    <i class="fas fa-external-link-alt me-1"></i>View on Google Maps
                                </a>
                            </div>
                            
                            <div class="contact-item mb-4" id="contact-phone-section">
                                <h5 class="fw-bold mb-2">
                                    <i class="fas fa-phone me-2 text-primary"></i>Phone
                                </h5>
                                <p class="mb-1"><?php echo htmlspecialchars($salon_settings['phone'] ?? '(+251) 921456765'); ?></p>
                            </div>
                            
                            <div class="contact-item" id="contact-email-section">
                                <h5 class="fw-bold mb-2">
                                    <i class="fas fa-envelope me-2 text-primary"></i>Email
                                </h5>
                                <p class="mb-0"><?php echo htmlspecialchars($salon_settings['email'] ?? 'quickcut@gmail.com'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Working Hours Card -->
                <div class="card login-card shadow-lg" id="hours-card">
                    <div class="card-body p-4">
                        <h3 class="fw-bold mb-4 section-title" id="hours-title">
                            <i class="fas fa-clock me-2 text-primary"></i>Working Hours
                        </h3>
                        <p class="text-muted small mb-3" id="hours-subtitle">Always on time, every time</p>
                        
                        <div class="hours-list" id="hours-list">
                            <div class="hours-item mb-3" id="weekday-hours">
                                <div class="d-flex justify-content-between">
                                    <strong>Monday - Sunday</strong>
                                    <span>6:00 AM - 6:00 PM</span>
                                </div>
                            </div>
                        </div>
                        <div class="walk-in-notice mt-4 p-3 bg-light rounded" id="walk-in-notice">
                            <i class="fas fa-door-open me-2 text-primary"></i>
                            <span class="small" id="walk-in-text">Walk-ins always welcome</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php include 'includes/footer.php'; ?>
    
    <!-- Custom About JavaScript -->
    <script src="assets/js/aboutus.js"></script>
</body>
</html>
