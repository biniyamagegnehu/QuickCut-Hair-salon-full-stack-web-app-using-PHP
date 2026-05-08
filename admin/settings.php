<?php
require_once 'admin_auth.php';
check_admin_page();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuickCut - Admin Settings</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/settings.css">
</head>
<body class="admin-settings-page">
    <!-- Sidebar (Existing Sidebar) -->
    <nav class="sidebar">
        <div class="sidebar-header">
            <a href="dashboard.php" class="sidebar-brand">
                <i class="fas fa-cut me-2"></i>QuickCut Admin
            </a>
        </div>
        
        <div class="sidebar-menu">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="appointments.php">
                        <i class="fas fa-calendar-alt"></i>
                        Appointments
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="barbers.php">
                        <i class="fas fa-user-tie"></i>
                        Barbers
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="services.php">
                        <i class="fas fa-cut"></i>
                        Services
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="working_hours.php">
                        <i class="fas fa-clock"></i>
                        Working Hours
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="customers.php">
                        <i class="fas fa-users"></i>
                        Customers
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="reports.php">
                        <i class="fas fa-chart-bar"></i>
                        Reports
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="settings.php">
                        <i class="fas fa-cog"></i>
                        Settings
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="sidebar-footer">
            <div class="admin-profile" id="sidebar-admin-profile">
                <div class="admin-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="admin-info">
                    <h6 class="mb-0" id="sidebar-admin-name"><?php echo htmlspecialchars($_SESSION['name'] ?? 'Admin User'); ?></h6>
                    <small class="text-muted">Administrator</small>
                </div>
                <a href="../auth/logout.php" class="logout-btn ms-auto" title="Logout" id="nav-logout">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Navigation -->
        <nav class="top-nav">
            <button class="sidebar-toggle">
                <i class="fas fa-bars"></i>
            </button>
            <div class="top-nav-items ms-auto">
                <div class="theme-toggle me-3">
                    <button id="theme-toggle-btn" class="btn btn-outline-secondary btn-sm rounded-circle">
                        <i class="fas fa-moon"></i>
                    </button>
                </div>
                <div class="date-time">
                    <span id="current-date"></span>
                    <span id="current-time"></span>
                </div>
            </div>
        </nav>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <div class="section-header">
                <h2><i class="fas fa-cog me-2"></i>Admin Settings</h2>
                <p class="text-muted">Manage your account, salon information, and website content.</p>
            </div>
            
            <div class="settings-container">
                <div class="row g-4">
                    <!-- Settings Sidebar Tabs -->
                    <div class="col-lg-3">
                        <div class="card settings-sidebar-card">
                            <div class="card-body p-0">
                                <div class="nav flex-column nav-pills" id="settings-tabs" role="tablist" aria-orientation="vertical">
                                    <button class="nav-link active" id="tab-account" data-bs-toggle="pill" data-bs-target="#content-account" type="button" role="tab">
                                        <i class="fas fa-user-circle me-2"></i>Account Settings
                                    </button>
                                    <button class="nav-link" id="tab-password" data-bs-toggle="pill" data-bs-target="#content-password" type="button" role="tab">
                                        <i class="fas fa-key me-2"></i>Change Password
                                    </button>
                                    <button class="nav-link" id="tab-salon" data-bs-toggle="pill" data-bs-target="#content-salon" type="button" role="tab">
                                        <i class="fas fa-store me-2"></i>Salon Information
                                    </button>
                                    <button class="nav-link" id="tab-about" data-bs-toggle="pill" data-bs-target="#content-about" type="button" role="tab">
                                        <i class="fas fa-info-circle me-2"></i>About Page Content
                                    </button>
                                    <button class="nav-link" id="tab-notifications" data-bs-toggle="pill" data-bs-target="#content-notifications" type="button" role="tab">
                                        <i class="fas fa-bell me-2"></i>Notifications
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Settings Content Area -->
                    <div class="col-lg-9">
                        <div class="tab-content" id="settings-tabContent">
                            <!-- Account Settings -->
                            <div class="tab-pane fade show active" id="content-account" role="tabpanel">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Account Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <form id="admin-profile-form">
                                            <div class="row align-items-center mb-4">
                                                <div class="col-auto">
                                                    <div class="profile-image-wrapper">
                                                        <img src="../assets/img/default-avatar.png" alt="Profile" id="admin-profile-preview" class="rounded-circle shadow">
                                                        <label for="admin-profile-image" class="profile-image-upload">
                                                            <i class="fas fa-camera"></i>
                                                        </label>
                                                        <input type="file" id="admin-profile-image" name="profile_image" hidden accept="image/*">
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <h5 class="mb-1" id="admin-display-name">Admin User</h5>
                                                    <p class="text-muted mb-0">Administrator</p>
                                                </div>
                                            </div>
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Full Name</label>
                                                    <input type="text" class="form-control" name="name" id="admin-name" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Email Address</label>
                                                    <input type="email" class="form-control" name="email" id="admin-email" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Phone Number</label>
                                                    <input type="tel" class="form-control" name="phone" id="admin-phone">
                                                </div>
                                                <div class="col-12 mt-4">
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-save me-2"></i>Save Changes
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Change Password -->
                            <div class="tab-pane fade" id="content-password" role="tabpanel">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Change Password</h5>
                                    </div>
                                    <div class="card-body">
                                        <form id="admin-password-form">
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <label class="form-label">Current Password</label>
                                                    <div class="input-group">
                                                        <input type="password" class="form-control" name="current_password" required>
                                                        <button class="btn btn-outline-secondary toggle-password" type="button">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">New Password</label>
                                                    <div class="input-group">
                                                        <input type="password" class="form-control" name="new_password" id="new-password" required minlength="6">
                                                        <button class="btn btn-outline-secondary toggle-password" type="button">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </div>
                                                    <div class="password-strength-meter mt-2">
                                                        <div class="progress" style="height: 5px;">
                                                            <div class="progress-bar" id="password-strength-bar" role="progressbar"></div>
                                                        </div>
                                                        <small id="password-strength-text" class="text-muted"></small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Confirm New Password</label>
                                                    <div class="input-group">
                                                        <input type="password" class="form-control" name="confirm_password" required>
                                                        <button class="btn btn-outline-secondary toggle-password" type="button">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="col-12 mt-4">
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-key me-2"></i>Update Password
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Salon Information -->
                            <div class="tab-pane fade" id="content-salon" role="tabpanel">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Salon Details (CMS)</h5>
                                    </div>
                                    <div class="card-body">
                                        <form id="salon-settings-form">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Salon Name</label>
                                                    <input type="text" class="form-control" name="salon_name" id="salon-name" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Tagline/Slogan <span class="text-muted small">(Optional)</span></label>
                                                    <input type="text" class="form-control" name="tagline" id="salon-tagline">
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label">Address <span class="text-muted small">(Optional)</span></label>
                                                    <textarea class="form-control" name="address" id="salon-address" rows="2"></textarea>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Phone Number <span class="text-muted small">(Optional)</span></label>
                                                    <input type="text" class="form-control" name="phone" id="salon-phone">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Business Email <span class="text-muted small">(Optional)</span></label>
                                                    <input type="email" class="form-control" name="email" id="salon-email">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Facebook Link <span class="text-muted small">(Optional)</span></label>
                                                    <input type="url" class="form-control" name="facebook_link" id="salon-facebook">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Instagram Link <span class="text-muted small">(Optional)</span></label>
                                                    <input type="url" class="form-control" name="instagram_link" id="salon-instagram">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Telegram Link <span class="text-muted small">(Optional)</span></label>
                                                    <input type="url" class="form-control" name="telegram_link" id="salon-telegram">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Google Maps Link <span class="text-muted small">(Optional)</span></label>
                                                    <input type="url" class="form-control" name="map_link" id="salon-map">
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label">Salon Logo <span class="text-muted small">(Optional)</span></label>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <img src="../assets/img/logo.png" alt="Logo" id="salon-logo-preview" style="height: 60px; object-fit: contain;">
                                                        <input type="file" class="form-control" name="logo" id="salon-logo-input" accept="image/*">
                                                    </div>
                                                </div>
                                                <div class="col-12 mt-4">
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-save me-2"></i>Update Salon Info
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- About Page Content -->
                            <div class="tab-pane fade" id="content-about" role="tabpanel">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">About Page Content (CMS)</h5>
                                    </div>
                                    <div class="card-body">
                                        <form id="about-content-form">
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <label class="form-label">About Title</label>
                                                    <input type="text" class="form-control" name="about_title" id="about-title" required>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label">About Description</label>
                                                    <textarea class="form-control" name="about_description" id="about-description" rows="3"></textarea>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Mission Statement</label>
                                                    <textarea class="form-control" name="mission" id="about-mission" rows="3"></textarea>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Vision Statement</label>
                                                    <textarea class="form-control" name="vision" id="about-vision" rows="3"></textarea>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label">Our Story/History</label>
                                                    <textarea class="form-control" name="story" id="about-story" rows="4"></textarea>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label">About Image</label>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <img src="../assets/img/about.jpg" alt="About" id="about-image-preview" style="height: 100px; width: 150px; object-fit: cover; border-radius: 8px;">
                                                        <input type="file" class="form-control" name="about_image" id="about-image-input" accept="image/*">
                                                    </div>
                                                </div>
                                                <div class="col-12 mt-4">
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-save me-2"></i>Update About Content
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- Notifications -->
                            <div class="tab-pane fade" id="content-notifications" role="tabpanel">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Notification Preferences</h5>
                                    </div>
                                    <div class="card-body">
                                        <form id="notification-settings-form">
                                            <div class="list-group list-group-flush">
                                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                    <div>
                                                        <h6 class="mb-0">Booking Notifications</h6>
                                                        <small class="text-muted">Get alerted when a customer makes a new booking</small>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" checked>
                                                    </div>
                                                </div>
                                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                    <div>
                                                        <h6 class="mb-0">Queue Alerts</h6>
                                                        <small class="text-muted">Receive alerts when the queue is becoming long</small>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" checked>
                                                    </div>
                                                </div>
                                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                    <div>
                                                        <h6 class="mb-0">Payment Notifications</h6>
                                                        <small class="text-muted">Get notified on successful customer payments</small>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" checked>
                                                    </div>
                                                </div>
                                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                    <div>
                                                        <h6 class="mb-0">System Maintenance</h6>
                                                        <small class="text-muted">Receive notifications about system updates and maintenance</small>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-4">
                                                <button type="button" class="btn btn-primary" onclick="showToast('Notification settings saved!', 'success')">
                                                    <i class="fas fa-save me-2"></i>Save Preferences
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Toast Notification Container -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3"></div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- JavaScript Files -->
    <script src="js/common.js"></script>
    <script src="js/settings.js"></script>
</body>
</html>
