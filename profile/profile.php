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
                                <i class="fas fa-user-edit me-2"></i> Personal Info
                            </button>
                            <button class="nav-link text-start" id="v-pills-history-tab" data-bs-toggle="pill" data-bs-target="#v-pills-history" type="button" role="tab">
                                <i class="fas fa-history me-2"></i> Booking History
                            </button>
                            <?php if ($_SESSION['auth_provider'] === 'local'): ?>
                            <button class="nav-link text-start" id="v-pills-password-tab" data-bs-toggle="pill" data-bs-target="#v-pills-password" type="button" role="tab">
                                <i class="fas fa-key me-2"></i> Change Password
                            </button>
                            <?php endif; ?>
                            <a href="<?php echo BASE_URL; ?>auth/logout.php" class="nav-link text-start text-danger">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
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

<?php 
include __DIR__ . '/../includes/footer.php'; 
?>
<script src="<?php echo BASE_URL; ?>assets/js/profile.js"></script>
