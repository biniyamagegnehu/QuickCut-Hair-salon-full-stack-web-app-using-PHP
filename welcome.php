<?php
session_start();
require_once 'includes/db.php';
$is_logged_in = isset($_SESSION['user_id']);

// Fetch services
$stmt = $pdo->query("SELECT * FROM services ORDER BY price ASC LIMIT 3");
$services = $stmt->fetchAll();

include 'includes/header.php';
?>

    <!-- Hero Section -->
    <section class="hero-section" id="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7" data-aos="fade-right">
                    <div class="hero-content">
                        <h1 class="hero-title fw-bold">
                            Elevate Your <span>Style</span>, Skip the Wait.
                        </h1>
                        <p class="hero-description">Experience the next generation of grooming. Book your session instantly and track your place in the queue in real-time. Professional cuts, zero waiting.</p>
                        <div class="hero-btns d-flex flex-wrap gap-3">
                            <a href="<?php echo $is_logged_in ? 'booking/bookappointment.php' : 'auth/login.php'; ?>" class="btn btn-accent">
                                <i class="fas fa-scissors me-2"></i>Book Now
                            </a>
                            <a href="#how-it-works" class="btn btn-outline-white">
                                <i class="fas fa-play me-2"></i>How It Works
                            </a>
                        </div>
                        
                        <!-- Animated Stats -->
                        <div class="hero-stats mt-5 pt-4">
                            <div class="row g-4">
                                <div class="col-4">
                                    <h3 class="fw-bold text-accent mb-0 stat-number" data-target="5000" data-suffix="+">0</h3>
                                    <p class="small text-white-50">Happy Clients</p>
                                </div>
                                <div class="col-4">
                                    <h3 class="fw-bold text-accent mb-0 stat-number" data-target="98" data-suffix="%">0</h3>
                                    <p class="small text-white-50">Satisfaction</p>
                                </div>
                                <div class="col-4">
                                    <h3 class="fw-bold text-accent mb-0 stat-number" data-target="15" data-suffix=" min">0</h3>
                                    <p class="small text-white-50">Avg. Wait</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 mt-5 mt-lg-0" data-aos="fade-left">
                    <div class="glass-card">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="mb-0 text-white"><i class="fas fa-users-viewfinder me-2"></i>Live Queue</h4>
                            <span class="badge bg-danger rounded-pill px-3">LIVE</span>
                        </div>
                        <div class="text-center py-4">
                            <p class="text-white-50 mb-1">Next Available Slot</p>
                            <h2 class="display-3 fw-bold text-accent" id="next-slot-time">3:15 PM</h2>
                        </div>
                        <div class="queue-details bg-white bg-opacity-10 p-3 rounded-4 mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0 text-white">Currently Waiting</h6>
                                    <small class="text-white-50">Estimated wait time: 25 mins</small>
                                </div>
                                <div class="text-end">
                                    <h2 class="mb-0 text-accent fw-bold" id="waiting-count">3</h2>
                                </div>
                            </div>
                        </div>
                        <a href="<?php echo $is_logged_in ? 'queue/queuestatus.php' : 'auth/login.php'; ?>" class="btn btn-accent w-100 py-3 fw-bold">
                            View Full Queue
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="section-padding bg-light">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h6 class="text-primary text-uppercase fw-bold mb-2">Why QuickCut?</h6>
                <h2 class="section-title">Smart Grooming Solutions</h2>
            </div>
            <div class="row g-4 mt-4">
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-clock"></i></div>
                        <h4 class="fw-bold">Real-time Queue</h4>
                        <p class="text-muted">Track your place in line live from your phone. No more sitting in waiting rooms.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-calendar-check"></i></div>
                        <h4 class="fw-bold">Online Booking</h4>
                        <p class="text-muted">Pick your favorite barber and service in seconds with our intuitive interface.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-bell"></i></div>
                        <h4 class="fw-bold">Instant Alerts</h4>
                        <p class="text-muted">Get notified exactly when it's your turn so you never miss your slot.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-shield-halved"></i></div>
                        <h4 class="fw-bold">Secure Payments</h4>
                        <p class="text-muted">Safe and easy online deposits via Chapa to secure your appointment.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fab fa-google"></i></div>
                        <h4 class="fw-bold">Google Sign-In</h4>
                        <p class="text-muted">Quick access to your account with Google integration. One click and you're in.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-star"></i></div>
                        <h4 class="fw-bold">Top Professionals</h4>
                        <p class="text-muted">Work with the best-rated barbers and stylists in the city.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="section-padding how-it-works overflow-hidden">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h6 class="text-accent text-uppercase fw-bold mb-2">Process</h6>
                <h2 class="display-4 fw-bold text-white">How It Works</h2>
            </div>
            <div class="row g-0 mt-5">
                <div class="col-lg-4" data-aos="fade-right" data-aos-delay="100">
                    <div class="step-card text-center">
                        <span class="step-number">01</span>
                        <div class="feature-icon mx-auto" style="background: var(--accent); color: var(--black);"><i class="fas fa-search"></i></div>
                        <h4 class="fw-bold mt-4">Select Service</h4>
                        <p class="text-white-50">Browse our services and pick your favorite barber and available time slot.</p>
                    </div>
                </div>
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="step-card text-center">
                        <span class="step-number">02</span>
                        <div class="feature-icon mx-auto" style="background: var(--accent); color: var(--black);"><i class="fas fa-credit-card"></i></div>
                        <h4 class="fw-bold mt-4">Secure Deposit</h4>
                        <p class="text-white-50">Pay a small commitment deposit securely to confirm your reservation.</p>
                    </div>
                </div>
                <div class="col-lg-4" data-aos="fade-left" data-aos-delay="300">
                    <div class="step-card text-center">
                        <span class="step-number">03</span>
                        <div class="feature-icon mx-auto" style="background: var(--accent); color: var(--black);"><i class="fas fa-chair"></i></div>
                        <h4 class="fw-bold mt-4">Get Groomed</h4>
                        <p class="text-white-50">Arrive on time, skip the waiting line, and enjoy your premium service.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="section-padding">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h6 class="text-primary text-uppercase fw-bold mb-2">Menu</h6>
                <h2 class="section-title">Premium Services</h2>
            </div>
            <div class="row g-4 mt-4">
                <?php foreach ($services as $index => $service): 
                    $delay = ($index + 1) * 100;
                    $icon = 'fa-cut';
                    $lower_name = strtolower($service['name']);
                    if (strpos($lower_name, 'beard') !== false) $icon = 'fa-user-tie';
                    elseif (strpos($lower_name, 'premium') !== false) $icon = 'fa-crown';
                ?>
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                    <div class="service-card h-100">
                        <div class="service-image" style="background-image: url('https://images.unsplash.com/photo-1585747860715-2ba37e788b70?auto=format&fit=crop&q=80&w=800');"></div>
                        <div class="service-content">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h4 class="fw-bold mb-0"><?php echo htmlspecialchars($service['name']); ?></h4>
                                <div class="text-primary"><i class="fas <?php echo $icon; ?> fa-lg"></i></div>
                            </div>
                            <p class="text-muted small">Our master barbers provide premium treatment using high-end tools and products for a perfect look.</p>
                            <div class="price-tag border-top pt-3">
                                <div class="price"><?php echo $service['price']; ?> ETB</div>
                                <div class="duration text-muted small"><i class="far fa-clock me-1"></i><?php echo $service['duration']; ?> min</div>
                            </div>
                            <a href="<?php echo $is_logged_in ? 'booking/bookappointment.php' : 'auth/login.php'; ?>" class="btn btn-primary w-100 rounded-pill py-2">Book Appointment</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="section-padding bg-light">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h6 class="text-primary text-uppercase fw-bold mb-2">Testimonials</h6>
                <h2 class="section-title">What Our Clients Say</h2>
            </div>
            <div class="row mt-5">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="testimonial-card">
                        <div class="d-flex align-items-center mb-4">
                            <img src="https://i.pravatar.cc/150?u=1" alt="User" class="user-img">
                            <div>
                                <h6 class="fw-bold mb-0">Dawit Tesfaye</h6>
                                <div class="text-warning small">
                                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted italic">"The live queue system is a game changer. I booked from my office, arrived 5 minutes before my slot, and was in the chair immediately."</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="testimonial-card">
                        <div class="d-flex align-items-center mb-4">
                            <img src="https://i.pravatar.cc/150?u=2" alt="User" class="user-img">
                            <div>
                                <h6 class="fw-bold mb-0">Samuel Bekele</h6>
                                <div class="text-warning small">
                                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted italic">"Best haircut experience in the city. The barbers are truly professional and the web app makes everything so seamless."</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="testimonial-card">
                        <div class="d-flex align-items-center mb-4">
                            <img src="https://i.pravatar.cc/150?u=3" alt="User" class="user-img">
                            <div>
                                <h6 class="fw-bold mb-0">Abel Girma</h6>
                                <div class="text-warning small">
                                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted italic">"I love the transparent pricing and the easy payment system. No more carrying cash or waiting in line for hours."</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section-padding bg-primary text-white text-center position-relative overflow-hidden">
        <div class="container position-relative z-1">
            <h2 class="display-4 fw-bold mb-4" data-aos="zoom-in">Ready to Transform Your Look?</h2>
            <p class="lead mb-5 opacity-75" data-aos="fade-up" data-aos-delay="100">Join thousands of satisfied customers who have skipped the line with QuickCut.</p>
            <div class="d-flex justify-content-center gap-3" data-aos="fade-up" data-aos-delay="200">
                <a href="<?php echo $is_logged_in ? 'booking/bookappointment.php' : 'auth/login.php'; ?>" class="btn btn-accent btn-lg px-5 py-3 rounded-pill fw-bold">Book Now</a>
                <a href="<?php echo BASE_URL; ?>aboutus.php" class="btn btn-outline-light btn-lg px-5 py-3 rounded-pill fw-bold">Learn More</a>
            </div>
        </div>
        <!-- Decorative shapes -->
        <div class="position-absolute top-0 start-0 w-100 h-100 opacity-25" style="background: radial-gradient(circle at 10% 10%, var(--accent) 0%, transparent 40%);"></div>
    </section>

<?php include 'includes/footer.php'; ?>

