<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: ../welcome.php");
    exit;
}
$is_logged_in = false; // Always false if they reach here due to redirect above
?>
<?php
$page_title = 'Create Account - QuickCut';
$additional_css = ['../assets/css/register.css'];
include '../includes/header.php';
?>

    <!-- Main Content -->
    <main class="container mt-5 pt-5" id="create-main">
        <div class="row align-items-center min-vh-75">
            <!-- Create Account Form Column -->
            <div class="col-lg-6 mb-5 mb-lg-0" id="create-form-column">
                <div class="card login-card shadow-lg" id="create-card">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <h2 class="fw-bold text-primary" id="create-title">Join QuickCut</h2>
                            <p class="text-muted" id="create-subtitle">Create your account in just a few steps</p>
                        </div>
                        
                        <form action="#" method="get" id="create-form">
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="firstName" class="form-label fw-semibold">First Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="first-name-icon">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <input type="text" class="form-control" id="create-first-name" placeholder="Abebe" required>
                                    </div>
                                    <div class="invalid-feedback" id="first-name-error"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="lastName" class="form-label fw-semibold">Last Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="last-name-icon">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <input type="text" class="form-control" id="create-last-name" placeholder="Kebede" required>
                                    </div>
                                    <div class="invalid-feedback" id="last-name-error"></div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="email-icon">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" class="form-control" id="create-email" placeholder="your@email.com" required>
                                </div>
                                <div class="invalid-feedback" id="email-error"></div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="phone" class="form-label fw-semibold">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="phone-icon">
                                        <i class="fas fa-phone"></i>
                                    </span>
                                    <input type="tel" class="form-control" id="create-phone" placeholder="0912345678" required>
                                </div>
                                <div class="invalid-feedback" id="phone-error"></div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="password-icon">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" class="form-control" id="create-password" placeholder="Create a strong password" required>
                                    <button type="button" class="btn btn-outline-secondary" id="toggle-create-password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="form-text" id="password-help">
                                   
                                  
                                </div>
                                <div class="invalid-feedback" id="password-error"></div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label fw-semibold">Confirm Password</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="confirm-password-icon">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" class="form-control" id="create-confirm-password" placeholder="Re-enter your password" required>
                                    <button type="button" class="btn btn-outline-secondary" id="toggle-confirm-password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback" id="confirm-password-error"></div>
                            </div>
                            
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="create-terms-check" required>
                                <label class="form-check-label small" for="create-terms-check" id="terms-label">
                                    I agree to the 
                                    <a href="#" class="text-decoration-none text-primary" id="terms-link">Terms of Service</a> 
                                    and 
                                    <a href="#" class="text-decoration-none text-primary" id="privacy-link">Privacy Policy</a>
                                </label>
                                <div class="invalid-feedback" id="terms-error">You must agree to the terms</div>
                            </div>
                            
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="create-marketing-check">
                                <label class="form-check-label small" for="create-marketing-check" id="marketing-label">
                                    I want to receive updates about new services and promotions
                                </label>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-lg w-100 mt-3" id="create-account-btn">
                                <i class="fas fa-user-plus me-2"></i>Create Account
                            </button>
                            
                            <div class="text-center mt-3" id="create-message"></div>
                        </form>
                        
                        <div class="text-center mt-4 pt-3 border-top" id="login-section">
                            <p class="text-muted mb-3" id="existing-account-text">Already have an account?</p>
                            <a href="login.php" class="btn btn-outline-primary btn-lg w-100" id="login-btn">
                                <i class="fas fa-sign-in-alt me-2"></i>Log In
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Benefits Column -->
            <div class="col-lg-6" id="benefits-column">
                <div class="text-center text-lg-start">
                    <h1 class="display-4 fw-bold mb-3" id="benefits-title">
                        Welcome to 
                        <span class="text-primary">QuickCut</span>
                    </h1>
                    <p class="lead text-muted mb-4" id="benefits-description">
                        Join thousands of satisfied customers who trust QuickCut for their grooming needs. 
                        Experience the convenience of modern barbering.
                    </p>
                    
                    <div class="benefits mt-5" id="benefits-list">
                        <div class="benefit-item mb-4" id="benefit-1">
                            <div class="d-flex align-items-start">
                                <div class="benefit-icon me-3">
                                    <i class="fas fa-clock fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold" id="benefit-1-title">Quick & Easy Booking</h5>
                                    <p class="text-muted mb-0" id="benefit-1-desc">Book appointments in under 2 minutes with our intuitive system</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="benefit-item mb-4" id="benefit-2">
                            <div class="d-flex align-items-start">
                                <div class="benefit-icon me-3">
                                    <i class="fas fa-history fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold" id="benefit-2-title">Skip the Wait</h5>
                                    <p class="text-muted mb-0" id="benefit-2-desc">Real-time queue updates help you avoid long waiting times</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="benefit-item mb-4" id="benefit-3">
                            <div class="d-flex align-items-start">
                                <div class="benefit-icon me-3">
                                    <i class="fas fa-star fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold" id="benefit-3-title">Expert Barbers</h5>
                                    <p class="text-muted mb-0" id="benefit-3-desc">Access top-rated professionals with years of experience</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="benefit-item" id="benefit-4">
                            <div class="d-flex align-items-start">
                                <div class="benefit-icon me-3">
                                    <i class="fas fa-shield-alt fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold" id="benefit-4-title">Secure & Convenient</h5>
                                    <p class="text-muted mb-0" id="benefit-4-desc">Safe payments and easy appointment management</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Statistics -->
                    <div class="stats-section mt-5 pt-4 border-top" id="stats-section">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="stat-number" id="client-count">10K+</div>
                                <div class="stat-label small text-muted">Happy Clients</div>
                            </div>
                            <div class="col-4">
                                <div class="stat-number" id="barber-count">50+</div>
                                <div class="stat-label small text-muted">Expert Barbers</div>
                            </div>
                            <div class="col-4">
                                <div class="stat-number" id="booking-status">24/7</div>
                                <div class="stat-label small text-muted">Online Booking</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Back to Top Button -->
    <button class="btn btn-primary scroll-to-top" style="display: none;" id="back-to-top">
        <i class="fas fa-chevron-up"></i>
    </button>
<?php
$additional_scripts = ['../assets/js/auth.js', '../assets/js/register.js'];
include '../includes/footer.php';
?>
