<?php
session_start();
require_once __DIR__ . '/../includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "auth/login.php");
    exit;
}

$page_title = "My Profile - QuickCut";
$additional_css = [BASE_URL . "assets/css/profile.css"];
include __DIR__ . '/../includes/header.php';
?>

<div class="profile-page">
    <div class="container">
        <div class="row">
            <!-- Profile Overview Card -->
            <div class="col-lg-4 mb-4">
                <div class="profile-card fade-in-up">
                    <div class="profile-cover"></div>
                    <div class="profile-avatar-container">
                        <div class="profile-avatar-wrapper">
                            <img src="<?php echo BASE_URL; ?>assets/images/default-avatar.png" id="profile-avatar" class="profile-avatar" alt="Profile Picture">
                            <label for="avatar-input" class="avatar-edit-btn">
                                <i class="fas fa-camera"></i>
                                <input type="file" id="avatar-input" hidden accept="image/*">
                            </label>
                        </div>
                        <h4 class="mt-3 mb-1 fw-bold text-theme"><?php echo htmlspecialchars($_SESSION['name']); ?></h4>
                        <p class="text-theme-muted small mb-3"><?php echo htmlspecialchars($_SESSION['email']); ?></p>
                        
                        <div class="d-flex justify-content-center gap-2">
                            <div class="auth-badge <?php echo $_SESSION['auth_provider'] === 'google' ? 'auth-google' : ''; ?>">
                                <i class="<?php echo $_SESSION['auth_provider'] === 'google' ? 'fab fa-google' : 'fas fa-envelope'; ?>"></i>
                                <span><?php echo ucfirst($_SESSION['auth_provider']); ?> Account</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="profile-info mt-4 pt-0">
                        <div class="nav flex-column nav-pills profile-tabs" id="v-pills-tab" role="tablist">
                            <button class="nav-link active text-start" id="v-pills-info-tab" data-bs-toggle="pill" data-bs-target="#v-pills-info" type="button" role="tab">
                                <i class="fas fa-user-edit me-2"></i><span> Personal Info</span>
                            </button>
                            <button class="nav-link text-start" id="v-pills-history-tab" data-bs-toggle="pill" data-bs-target="#v-pills-history" type="button" role="tab">
                                <i class="fas fa-history me-2"></i><span> Booking History</span>
                            </button>
                            <?php if ($_SESSION['auth_provider'] === 'local'): ?>
                            <button class="nav-link text-start" id="v-pills-password-tab" data-bs-toggle="pill" data-bs-target="#v-pills-password" type="button" role="tab">
                                <i class="fas fa-key me-2"></i><span> Change Password</span>
                            </button>
                            <?php endif; ?>
                            <a href="<?php echo BASE_URL; ?>auth/logout.php" class="nav-link text-start text-danger">
                                <i class="fas fa-sign-out-alt me-2"></i><span> Logout</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Content Area -->
            <div class="col-lg-8">
                <div class="tab-content" id="v-pills-tabContent">
                    <!-- Personal Info Tab -->
                    <div class="tab-pane fade show active" id="v-pills-info" role="tabpanel">
                        <div class="card border-0 shadow-sm rounded-4 bg-theme-surface overflow-hidden fade-in-up">
                            <div class="card-header bg-transparent border-0 p-4 pb-0">
                                <h5 class="fw-bold mb-0">Edit Profile</h5>
                                <p class="text-muted small">Manage your basic information</p>
                            </div>
                            <div class="card-body p-4">
                                <form id="profile-form">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">Full Name</label>
                                            <input type="text" name="name" id="profile-name" class="form-control rounded-3" placeholder="Enter full name" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">Phone Number</label>
                                            <input type="tel" name="phone" id="profile-phone" class="form-control rounded-3" placeholder="e.g., 0912345678">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label small fw-bold">Email Address</label>
                                            <input type="email" id="profile-email" class="form-control rounded-3" disabled>
                                            <?php if ($_SESSION['auth_provider'] === 'google'): ?>
                                            <div class="form-text text-info small">
                                                <i class="fas fa-info-circle me-1"></i> Email managed by Google.
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-12 mt-4">
                                            <button type="submit" class="btn btn-primary rounded-pill px-4">Save Changes</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Booking History Tab -->
                    <div class="tab-pane fade" id="v-pills-history" role="tabpanel">
                        <div class="card border-0 shadow-sm rounded-4 bg-theme-surface overflow-hidden fade-in-up">
                            <div class="card-header bg-transparent border-0 p-4 pb-0 d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="fw-bold mb-0">Booking History</h5>
                                    <p class="text-muted small">Your recent grooming sessions</p>
                                </div>
                                <a href="<?php echo BASE_URL; ?>booking/bookappointment.php" class="btn btn-outline-primary btn-sm rounded-pill px-3">New Booking</a>
                            </div>
                            <div class="card-body p-4 pt-2">
                                <div id="booking-history-list">
                                    <!-- Loaded via JS -->
                                    <div class="text-center py-4">
                                        <div class="spinner-border text-primary spinner-border-sm" role="status"></div>
                                        <p class="small text-muted mt-2">Loading history...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Change Password Tab -->
                    <?php if ($_SESSION['auth_provider'] === 'local'): ?>
                    <div class="tab-pane fade" id="v-pills-password" role="tabpanel">
                        <div class="card border-0 shadow-sm rounded-4 bg-theme-surface overflow-hidden fade-in-up">
                            <div class="card-header bg-transparent border-0 p-4 pb-0">
                                <h5 class="fw-bold mb-0">Security Settings</h5>
                                <p class="text-muted small">Update your account password</p>
                            </div>
                            <div class="card-body p-4">
                                <form id="password-form">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label small fw-bold">Current Password</label>
                                            <input type="password" name="current_password" class="form-control rounded-3" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">New Password</label>
                                            <input type="password" name="new_password" class="form-control rounded-3" required minlength="6">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">Confirm New Password</label>
                                            <input type="password" name="confirm_password" class="form-control rounded-3" required minlength="6">
                                        </div>
                                        <div class="col-12 mt-4">
                                            <button type="submit" class="btn btn-primary rounded-pill px-4">Update Password</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Rating Modal -->
<div class="modal fade" id="ratingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-dark text-white border-0">
                <h5 class="modal-title fw-bold">Rate Your Barber</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <h6 class="text-muted mb-1" id="rating-barber-name">Barber Name</h6>
                    <p class="small text-muted" id="rating-appointment-info">Service Date & Time</p>
                    <div class="rating-stars-input fs-2 text-warning mb-2" id="star-rating">
                        <i class="far fa-star star-btn" data-value="1"></i>
                        <i class="far fa-star star-btn" data-value="2"></i>
                        <i class="far fa-star star-btn" data-value="3"></i>
                        <i class="far fa-star star-btn" data-value="4"></i>
                        <i class="far fa-star star-btn" data-value="5"></i>
                    </div>
                    <p class="small fw-bold text-primary" id="rating-desc">Select a rating</p>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Tell us about your experience (optional)</label>
                    <textarea class="form-control rounded-3" id="rating-review" rows="3" placeholder="How was the service?"></textarea>
                </div>
                <input type="hidden" id="rating-appointment-id">
                <input type="hidden" id="rating-barber-id">
                <input type="hidden" id="rating-value" value="0">
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary rounded-pill px-4" id="submit-rating-btn" disabled>Submit Rating</button>
            </div>
        </div>
    </div>
</div>

<?php 
include __DIR__ . '/../includes/footer.php'; 
?>
<script src="<?php echo BASE_URL; ?>assets/js/profile.js"></script>
