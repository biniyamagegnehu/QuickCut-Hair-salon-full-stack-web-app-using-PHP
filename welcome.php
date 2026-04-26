<?php
session_start();
require_once 'db.php';
$is_logged_in = isset($_SESSION['user_id']);

// Fetch services
$stmt = $pdo->query("SELECT * FROM services ORDER BY price ASC LIMIT 3");
$services = $stmt->fetchAll();

include 'header.php';
?>

    <!-- Hero Section -->
    <section class="hero-section" id="hero">
        <div class="container">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-7">
                    <div class="hero-content">
                        <h1 class="display-1 fw-bold mb-4" id="hero-title">
                            Welcome to <span class="text-primary">QuickCut</span>
                        </h1>
                        <h2 class="display-4 fw-light mb-4" id="hero-tagline">Skip the wait. <span class="fw-bold">Get the cut.</span></h2>
                        <p class="lead mb-5" id="hero-description">Professional grooming services tailored for the modern man. Book online and avoid long queues with our real-time scheduling system.</p>
                        <div class="d-flex flex-wrap gap-3 hero-buttons">
                            <a href="<?php echo $is_logged_in ? 'bookappointment.php' : 'login.php'; ?>" class="btn btn-primary btn-lg px-5 py-3" id="book-appointment-btn">
                                <i class="fas fa-calendar-check me-2"></i>Book Appointment Now
                            </a>
                            <a href="<?php echo $is_logged_in ? 'queuestatus.php' : 'login.php'; ?>" class="btn btn-outline-light btn-lg px-5 py-3" id="view-queue-btn">
                                <i class="fas fa-clock me-2"></i>View Live Queue
                            </a>
                        </div>
                        <div class="hero-stats mt-5 pt-4" id="hero-stats">
                            <div class="row">
                                <div class="col-md-3">
                                    <h3 class="fw-bold text-primary" id="clients-count">5000+</h3>
                                    <p class="text-light">Happy Clients</p>
                                </div>
                                <div class="col-md-3">
                                    <h3 class="fw-bold text-primary" id="satisfaction-rate">98%</h3>
                                    <p class="text-light">Satisfaction Rate</p>
                                </div>
                                <div class="col-md-3">
                                    <h3 class="fw-bold text-primary" id="wait-time">15 min</h3>
                                    <p class="text-light">Avg. Wait Time</p>
                                </div>
                                <div class="col-md-3">
                                    <h3 class="fw-bold text-primary" id="barbers-count">50+</h3>
                                    <p class="text-light">Expert Barbers</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="hero-image-container">
                        <div class="queue-card glass-card" id="queue-card">
                            <div class="queue-header">
                                <h3 class="text-white">
                                    <i class="fas fa-users me-2"></i>Live Queue Status
                                </h3>
                                <div class="live-indicator">
                                    <span class="dot"></span>
                                    <small>LIVE</small>
                                </div>
                            </div>
                            <div class="queue-body">
                                <div class="next-slot text-center mb-4">
                                    <h4 class="text-light">Next Available Slot</h4>
                                    <h2 class="display-3 fw-bold text-primary" id="next-slot-time">3:15 PM</h2>
                                </div>
                                <div class="queue-info">
                                    <div class="queue-item">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h5 class="fw-bold text-light">Currently Waiting</h5>
                                                <p class="text-muted">In queue right now</p>
                                            </div>
                                            <div class="text-end">
                                                <h2 class="fw-bold text-primary" id="waiting-count">3</h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a href="<?php echo $is_logged_in ? 'queuestatus.php' : 'login.php'; ?>" class="btn btn-light w-100 py-3 mt-4" id="refresh-queue">
                                    <i class="fas fa-sync-alt me-2"></i>Refresh Queue Status
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="services-section py-5">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h6 class="text-primary text-uppercase fw-bold">Our Premium Services</h6>
                <h2 class="display-4 fw-bold">Professional Grooming</h2>
                <p class="lead text-muted">Tailored for the modern man</p>
            </div>
            <div class="row g-4">
                <?php foreach ($services as $index => $service): 
                    $featured = ($index === 1) ? 'featured' : '';
                    $icon = 'fa-cut';
                    $lower_name = strtolower($service['name']);
                    if (strpos($lower_name, 'beard') !== false) $icon = 'fa-user-tie';
                    elseif (strpos($lower_name, 'premium') !== false) $icon = 'fa-crown';
                ?>
                <div class="col-lg-4">
                    <div class="service-card <?php echo $featured; ?>" id="service-card-<?php echo $service['id']; ?>">
                        <div class="service-image" style="background-image: url('https://images.unsplash.com/photo-1503951914875-452162b0f3f1?auto=format&fit=crop&q=80&w=800');"></div>
                        <div class="service-content">
                            <div class="service-icon"><i class="fas <?php echo $icon; ?>"></i></div>
                            <h4 class="fw-bold"><?php echo htmlspecialchars($service['name']); ?></h4>
                            <div class="price-tag">
                                <span class="price"><?php echo $service['price']; ?> Birr</span>
                                <span class="duration"><?php echo $service['duration']; ?> min</span>
                            </div>
                            <p class="text-muted">Professional salon service tailored for your needs.</p>
                            <a href="<?php echo $is_logged_in ? 'bookappointment.php' : 'login.php'; ?>" class="btn <?php echo $featured ? 'btn-primary' : 'btn-outline-primary'; ?> w-100 mt-3">Book This Service</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="why-section py-5 bg-dark text-white" id="why-us">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div class="why-image" style="height: 400px; background: url('https://images.unsplash.com/photo-1585747860715-2ba37e788b70?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80') center/cover no-repeat; border-radius: 15px;"></div>
                </div>
                <div class="col-lg-6">
                    <h6 class="text-primary text-uppercase fw-bold mb-3">Why Choose Us</h6>
                    <h2 class="display-5 fw-bold mb-4">Experience the <span class="text-primary">QuickCut</span> Difference</h2>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="feature-box p-4 bg-white bg-opacity-10 rounded-3">
                                <i class="fas fa-user-tie fa-2x text-primary mb-3"></i>
                                <h5 class="fw-bold">Expert Barbers</h5>
                                <p class="small opacity-75">Skilled professionals with years of experience.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="feature-box p-4 bg-white bg-opacity-10 rounded-3">
                                <i class="fas fa-clock fa-2x text-primary mb-3"></i>
                                <h5 class="fw-bold">No Long Waits</h5>
                                <p class="small opacity-75">Real-time queue system that saves your time.</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5">
                        <a href="<?php echo $is_logged_in ? 'bookappointment.php' : 'login.php'; ?>" class="btn btn-primary btn-lg px-5 py-3">
                            <i class="fas fa-calendar-plus me-2"></i>Book Your First Appointment
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php include 'footer.php'; ?>
