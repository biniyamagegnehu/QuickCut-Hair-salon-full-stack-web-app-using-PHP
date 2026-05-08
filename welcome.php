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
            <div class="hero-content fade-in-up" data-aos="fade-up">
                <h1 class="hero-title fw-bold">
                    Clean Cuts, Zero Wait.
                </h1>
                <p class="hero-subtitle">A modern barbershop experience with quick booking and live queue tracking.</p>
                <div class="hero-btns">
                    <a href="<?php echo $is_logged_in ? 'booking/bookappointment.php' : 'auth/login.php'; ?>" class="btn-premium btn-primary-cut">
                        Book Now
                    </a>
                    <a href="<?php echo $is_logged_in ? 'queue/queuestatus.php' : 'auth/login.php'; ?>" class="btn-premium btn-outline-cut">
                        View Queue
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Queue Status Quick Look -->
    <section class="bg-white py-4 shadow-sm border-bottom">
        <div class="container">
            <div class="row align-items-center justify-content-center text-center">
                <div class="col-md-3 border-end">
                    <p class="text-muted small text-uppercase mb-0 ls-1">Currently Serving</p>
                    <h5 class="fw-bold mb-0" id="next-slot-time">3:15 PM</h5>
                </div>
                <div class="col-md-3 border-end">
                    <p class="text-muted small text-uppercase mb-0 ls-1">Waiting</p>
                    <h5 class="fw-bold mb-0"><span id="waiting-count">3</span> Clients</h5>
                </div>
                <div class="col-md-3">
                    <p class="text-muted small text-uppercase mb-0 ls-1">Est. Wait Time</p>
                    <h5 class="fw-bold mb-0">~25 Mins</h5>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="section-padding" id="services">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <h2 class="section-title">Our Premium <span>Services</span></h2>
                <p class="section-subtitle">Experience precision and luxury with our curated range of professional grooming services.</p>
            </div>
            <div class="row g-4">
                <?php foreach ($services as $index => $service): ?>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                    <div class="service-card h-100">
                        <div class="service-img">
                            <?php if ($service['image_path']): ?>
                            <img src="<?php echo htmlspecialchars($service['image_path']); ?>" alt="<?php echo htmlspecialchars($service['name']); ?>">
                            <?php else: ?>
                            <img src="https://images.unsplash.com/photo-1585747860715-2ba37e788b70?auto=format&fit=crop&q=80&w=800" alt="<?php echo htmlspecialchars($service['name']); ?>">
                            <?php endif; ?>
                        </div>
                        <div class="service-body text-center">
                            <h4 class="fw-bold mb-3"><?php echo htmlspecialchars($service['name']); ?></h4>
                            <p class="text-muted small mb-4">Our master barbers provide premium treatment using high-end tools and products for a perfect look.</p>
                            <div class="service-price">ETB <?php echo number_format($service['price'], 2); ?></div>
                            <a href="<?php echo $is_logged_in ? 'booking/bookappointment.php' : 'auth/login.php'; ?>" class="btn-premium btn-primary-cut btn-sm py-2 px-4">
                                Book Now
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Experience Section -->
    <section class="section-padding bg-black text-white" id="how-it-works">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <h2 class="section-title text-white">The <span>Experience</span></h2>
                <p class="section-subtitle text-white-50">Modern grooming made simple. Three steps to looking your absolute best.</p>
            </div>
            <div class="row g-5">
                <div class="col-md-4" data-aos="fade-up">
                    <div class="step-card">
                        <div class="step-number">01</div>
                        <h4 class="fw-bold mb-3">Reserve</h4>
                        <p class="text-white-50">Select your service and professional barber through our seamless booking interface.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="step-card">
                        <div class="step-number">02</div>
                        <h4 class="fw-bold mb-3">Track</h4>
                        <p class="text-white-50">Monitor your position in our live queue in real-time from anywhere via your mobile device.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="step-card">
                        <div class="step-number">03</div>
                        <h4 class="fw-bold mb-3">Relax</h4>
                        <p class="text-white-50">Arrive just in time for your appointment and enjoy a premium grooming experience.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php include 'includes/footer.php'; ?>

