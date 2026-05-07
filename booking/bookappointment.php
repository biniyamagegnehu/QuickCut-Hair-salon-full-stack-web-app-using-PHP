<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$is_logged_in = true;
$user_name = '';
$user_email = '';
$user_phone = '';

$stmt = $pdo->prepare("SELECT name, email, phone FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
if ($user) {
    $user_name = $user['name'] ?? '';
    $user_email = $user['email'] ?? '';
    $user_phone = $user['phone'] ?? '';
}

// Fetch services
$stmt = $pdo->query("SELECT id, name, duration, price FROM services ORDER BY name ASC");
$services = $stmt->fetchAll();

// Fetch active barbers
$stmt = $pdo->query("SELECT id, first_name, last_name, specialty, rating FROM barbers WHERE status = 'active' ORDER BY first_name ASC");
$barbers = $stmt->fetchAll();
?>
<?php include '../includes/header.php'; ?>
    <!-- Custom Booking Styles -->
    <link rel="stylesheet" href="../assets/css/bookappointment.css">
    
    <!-- Payment styles -->
    <style>
        .payment-section {
            background: linear-gradient(135deg, #ffffff 0%, #f7fbff 100%);
            border: 1px solid #e6eef8;
            padding: 16px;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(13,110,253,0.06);
        }

        .payment-section h5 {
            font-weight: 600;
            color: var(--primary);
        }

        .payment-section .form-check {
            padding: 8px 0;
            border-bottom: 1px dashed #eef6ff;
        }

        .payment-section .form-check:last-of-type { border-bottom: none; }

        .payment-details {
            background: #ffffff;
            border-left: 3px solid rgba(13,110,253,0.06);
            padding: 10px 12px;
            border-radius: 8px;
            margin-bottom: 8px;
        }

        #telebirr-phone, #cbe-ref, #bank-ref {
            border-radius: 8px;
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.03);
        }

        .payment-small-note { font-size: 0.85rem; color: #6c757d; }

        .is-invalid { border-color: #dc3545 !important; }

        @media (max-width: 768px) {
            .payment-section { padding: 12px; }
        }
    </style>

    <!-- Main Content -->
    <main class="booking-page" id="booking-main">
        <!-- Header -->
        <header class="booking-header" id="booking-header">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h1 id="booking-title">Book Your Appointment</h1>
                        <p class="lead" id="booking-subtitle">Schedule your visit with our expert barbers in just a few clicks</p>
                        <div class="header-badges mt-4" id="booking-badges">
                            <span class="badge"><i class="fas fa-clock me-1"></i>No Waiting Time</span>
                            <span class="badge"><i class="fas fa-star me-1"></i>Expert Barbers</span>
                            <span class="badge"><i class="fas fa-shield-alt me-1"></i>Hygiene First</span>
                        </div>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <div class="header-stats" id="header-stats" style="display: none;">
                            <!-- Removed number of slots as requested -->
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Booking Form -->
        <div class="container booking-container" id="booking-container">
            <div class="row">
                <!-- Left Column - Form -->
                <div class="col-lg-8" id="booking-left-column">
                    <div class="booking-form-card" id="booking-form-card">
                        <!-- Progress -->
                        <div class="booking-progress" id="booking-progress">
                            <div class="progress-step active" id="progress-step-1">
                                <span>1</span>
                                <div class="step-label">Personal Info</div>
                            </div>
                            <div class="progress-step" id="progress-step-2">
                                <span>2</span>
                                <div class="step-label">Select Barber</div>
                            </div>
                            <div class="progress-step" id="progress-step-3">
                                <span>3</span>
                                <div class="step-label">Choose Time</div>
                            </div>
                            <div class="progress-step" id="progress-step-4">
                                <span>4</span>
                                <div class="step-label">Confirm</div>
                            </div>
                            <div class="progress-step" id="progress-step-4">
                                <span>5</span>
                                <div class="step-label">payment</div>
                            </div>
                        </div>

                        <!-- Personal Info -->
                        <form action="queuestatus.html" method="get" id="booking-form">
                            <?php 
                            $hide_personal_info = $is_logged_in && !empty($user_name) && !empty($user_email) && !empty($user_phone);
                            ?>
                            <div class="form-section" id="personal-info-section" <?php echo $hide_personal_info ? 'style="display: none;"' : ''; ?>>
                                <h3><i class="fas fa-user me-2 text-primary"></i>Personal Information</h3>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Full Name *</label>
                                        <div class="input-with-icon">
                                            <i class="fas fa-user"></i>
                                            <input type="text" class="form-control" id="booking-full-name" placeholder="Enter your full name" required value="<?php echo htmlspecialchars($user_name); ?>">
                                        </div>
                                        <div class="invalid-feedback" id="name-error"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Phone Number *</label>
                                        <div class="input-with-icon">
                                            <i class="fas fa-phone"></i>
                                            <input type="tel" class="form-control" id="booking-phone" placeholder="0912345678" required value="<?php echo htmlspecialchars($user_phone); ?>">
                                        </div>
                                        <div class="invalid-feedback" id="phone-error"></div>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Email Address *</label>
                                        <div class="input-with-icon">
                                            <i class="fas fa-envelope"></i>
                                            <input type="email" class="form-control" id="booking-email" placeholder="your@email.com" required value="<?php echo htmlspecialchars($user_email); ?>">
                                        </div>
                                        <div class="invalid-feedback" id="email-error"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-section" id="service-selection-section">
                                <h3><i class="fas fa-cut me-2 text-primary"></i>Select Service *</h3>
                                <div class="service-options" id="service-options">
                                    <?php foreach ($services as $index => $service): 
                                        $icon_class = 'haircut-icon';
                                        $icon_fa = 'fa-cut';
                                        
                                        $lower_name = strtolower($service['name']);
                                        if (strpos($lower_name, 'beard') !== false) {
                                            $icon_class = 'beard-icon';
                                            $icon_fa = 'fa-user-tie';
                                        } elseif (strpos($lower_name, 'premium') !== false || strpos($lower_name, 'package') !== false) {
                                            $icon_class = 'premium-icon';
                                            $icon_fa = 'fa-crown';
                                        }
                                    ?>
                                    <div class="service-option" id="service-option-<?php echo $service['id']; ?>">
                                        <input type="radio" name="service" id="service<?php echo $service['id']; ?>" value="<?php echo htmlspecialchars($service['name']); ?>" data-price="<?php echo $service['price']; ?> Birr" required>
                                        <label for="service<?php echo $service['id']; ?>">
                                            <div class="service-icon <?php echo $icon_class; ?>">
                                                <i class="fas <?php echo $icon_fa; ?>"></i>
                                            </div>
                                            <div class="service-info">
                                                <h4 id="service-<?php echo $service['id']; ?>-title"><?php echo htmlspecialchars($service['name']); ?></h4>
                                                <p id="service-<?php echo $service['id']; ?>-desc">Professional salon service</p>
                                                <div class="service-price">
                                                    <span class="price" id="service-<?php echo $service['id']; ?>-price"><?php echo $service['price']; ?> Birr</span>
                                                    <span class="duration" id="service-<?php echo $service['id']; ?>-duration"><?php echo $service['duration']; ?> min</span>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="form-section" id="barber-selection-section">
                                <h3><i class="fas fa-user-tie me-2 text-primary"></i>Select Barber *</h3>
                                <div class="barber-options" id="barber-options">
                                    <?php foreach ($barbers as $barber): 
                                        $full_name = $barber['first_name'] . ' ' . $barber['last_name'];
                                    ?>
                                    <div class="barber-option" id="barber-option-<?php echo $barber['id']; ?>">
                                        <input type="radio" name="barber" id="barber<?php echo $barber['id']; ?>" 
                                               value="<?php echo htmlspecialchars($full_name); ?>" 
                                               data-specialty="<?php echo htmlspecialchars($barber['specialty']); ?>"
                                               data-rating="<?php echo $barber['rating']; ?>"
                                               required>
                                        <label for="barber<?php echo $barber['id']; ?>">
                                            <div class="barber-avatar">
                                                <i class="fas fa-user-tie"></i>
                                            </div>
                                            <div class="barber-info">
                                                <h5 id="barber-<?php echo $barber['id']; ?>-name"><?php echo htmlspecialchars($full_name); ?></h5>
                                                <p id="barber-<?php echo $barber['id']; ?>-specialty"><?php echo htmlspecialchars($barber['specialty']); ?></p>
                                                <div class="rating">
                                                    <i class="fas fa-star text-warning"></i>
                                                    <span id="barber-<?php echo $barber['id']; ?>-rating"><?php echo $barber['rating']; ?></span>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Date & Time -->
                            <div class="form-section" id="datetime-section">
                                <!-- Date Picker Row -->
                                <div id="date-container-col">
                                    <div class="dt-section-header mb-3">
                                        <div>
                                            <h5 class="mb-1"><i class="fas fa-calendar-alt me-2"></i>Select Date</h5>
                                            <p class="text-muted mb-0 small">Choose a date for your appointment</p>
                                        </div>
                                        <div id="real-time-status" class="dt-status-badge"></div>
                                    </div>
                                    <!-- Skeleton strip (replaced by JS) -->
                                    <div class="date-skeleton-strip" id="date-skeleton-strip">
                                        <div class="skeleton-date-card"></div>
                                        <div class="skeleton-date-card"></div>
                                        <div class="skeleton-date-card"></div>
                                        <div class="skeleton-date-card"></div>
                                        <div class="skeleton-date-card"></div>
                                    </div>
                                    <div class="date-list" id="date-list"></div>
                                    <div id="date-info" class="mt-3" style="display:none;"></div>
                                </div>

                                <!-- Divider -->
                                <div class="dt-divider"></div>

                                <!-- Time Picker Row -->
                                <div id="time-container-col">
                                    <div class="dt-section-header mb-3">
                                        <div>
                                            <h5 class="mb-1"><i class="fas fa-clock me-2"></i>Select Time</h5>
                                            <p class="text-muted mb-0 small">Choose an available slot</p>
                                        </div>
                                        <div id="time-selected-badge" class="dt-status-badge" style="display:none;"></div>
                                    </div>
                                    <!-- Skeleton grid (replaced by JS) -->
                                    <div class="time-skeleton-grid" id="time-skeleton-strip">
                                        <div class="skeleton-time-card"></div>
                                        <div class="skeleton-time-card"></div>
                                        <div class="skeleton-time-card"></div>
                                        <div class="skeleton-time-card"></div>
                                        <div class="skeleton-time-card"></div>
                                        <div class="skeleton-time-card"></div>
                                    </div>
                                    <div id="time-slots-wrapper"></div>
                                </div>
                            </div>

                            <!-- Special Requests -->
                            <div class="form-section" id="special-requests-section">
                                <h4><i class="fas fa-edit me-2 text-primary"></i>Special Requests (Optional)</h4>
                                <textarea class="form-control" name="requests" id="special-requests" rows="3" placeholder="Any specific requirements or preferences?"></textarea>
                            </div>

                            <!-- Submit Button -->
                            <div class="form-submit" id="form-submit">
                                <!-- Payment Selection -->
                                <!-- Payment Notice -->
                                <div class="payment-section mb-3" id="payment-section">
                                    <h5 class="mb-2"><i class="fas fa-shield-alt me-2 text-primary"></i>Secure Payment</h5>
                                    <p class="small text-muted mb-3">To confirm your booking, a <strong>50% deposit</strong> is required. Payments are processed securely via Chapa.</p>
                                    
                                    <div class="deposit-notice p-3 border rounded bg-light">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>Required Deposit:</span>
                                            <span class="fw-bold text-primary" id="payment-deposit-amount">0.00 ETB</span>
                                        </div>
                                    </div>
                                    <p class="small text-muted mt-2 mb-0"><i class="fas fa-info-circle me-1"></i> You will be redirected to Chapa to complete the payment.</p>
                                </div>

                                <button type="submit" class="btn btn-primary btn-lg w-100" id="confirm-booking-btn">
                                    <i class="fas fa-calendar-check me-2"></i>Confirm Booking
                                </button>
                                <p class="terms mt-3" id="terms-text">
                                    By booking, you agree to our <a href="#" class="text-primary" id="terms-link">Terms</a> and <a href="#" class="text-primary" id="privacy-link">Privacy Policy</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Column - Summary -->
                <div class="col-lg-4" id="booking-right-column">
                    <!-- Booking Summary -->
                    <div class="summary-card" id="summary-card">
                        <h4><i class="fas fa-receipt me-2 text-primary"></i>Booking Summary</h4>
                        <div class="summary-item">
                            <span>Service:</span>
                            <strong id="summary-service">Select service</strong>
                        </div>
                        <div class="summary-item">
                            <span>Barber:</span>
                            <strong id="summary-barber">Select barber</strong>
                        </div>
                        <div class="summary-item">
                            <span>Date:</span>
                            <strong id="summary-date">Select date</strong>
                        </div>
                        <div class="summary-item">
                            <span>Time:</span>
                            <strong id="summary-time">Select time</strong>
                        </div>
                        <div class="summary-item mt-2" id="summary-deposit-row" style="display: none;">
                            <span>50% Deposit:</span>
                            <strong id="summary-deposit" class="text-warning">0.00 ETB</strong>
                        </div>
                        <div class="summary-total">
                            <span>Total Amount:</span>
                            <strong id="summary-price">0.00 ETB</strong>
                        </div>
                    </div>

                    <!-- Selected Barber Info -->
                    <div class="info-card" id="selected-barber-info" style="display: none;">
                        <h4><i class="fas fa-user-tie me-2 text-primary"></i>Selected Barber</h4>
                        <div class="selected-barber">
                            <div class="barber-avatar-lg">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="barber-info-lg">
                                <h5 id="selected-barber-name">Barber Name</h5>
                                <p id="selected-barber-specialty">Specialty</p>
                                <div class="barber-experience">
                                    <i class="fas fa-award text-primary"></i>
                                    <span id="selected-barber-exp">10+ years experience</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Features Card -->
                    <div class="features-card" id="features-card">
                        <h4><i class="fas fa-star me-2 text-primary"></i>Why Choose Us</h4>
                        <div class="features-list" id="features-list">
                            <div class="feature-item" id="feature-1">
                                <i class="fas fa-clock text-primary"></i>
                                <span>Quick Service</span>
                            </div>
                            <div class="feature-item" id="feature-2">
                                <i class="fas fa-shield-alt text-primary"></i>
                                <span>Hygiene First</span>
                            </div>
                            <div class="feature-item" id="feature-3">
                                <i class="fas fa-money-bill-wave text-primary"></i>
                                <span>Affordable Pricing</span>
                            </div>
                            <div class="feature-item" id="feature-4">
                                <i class="fas fa-calendar-check text-primary"></i>
                                <span>Easy Booking</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Tips -->
                    <div class="tips-card" id="tips-card">
                        <h4><i class="fas fa-lightbulb me-2 text-primary"></i>Quick Tips</h4>
                        <ul class="tips-list" id="tips-list">
                            <li id="tip-1"><i class="fas fa-check text-primary"></i> Arrive 5 minutes early</li>
                            <li id="tip-2"><i class="fas fa-check text-primary"></i> Cancel anytime before 2 hours</li>
                            <li id="tip-3"><i class="fas fa-check text-primary"></i> Bring reference photos if needed</li>
                            <li id="tip-4"><i class="fas fa-check text-primary"></i> Free consultation included</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php include '../includes/footer.php'; ?>
    
    <script src="../assets/js/auth.js"></script>
    <script src="../assets/js/bookappointment.js?v=<?php echo time(); ?>"></script>
</body>
</html>
