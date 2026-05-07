<?php
// header.php - Main Website Header
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/config.php';
$is_logged_in = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) : 'QuickCut - Professional Barber Services'; ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/home.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/theme.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/navbar.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/animations.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/notifications.css">
    <?php if (isset($additional_css) && is_array($additional_css)): ?>
        <?php foreach ($additional_css as $css_path): ?>
            <link rel="stylesheet" href="<?php echo htmlspecialchars($css_path); ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script>
        const BASE_URL = '<?php echo BASE_URL; ?>';
    </script>
</head>
<body id="main-body">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="main-nav">
        <div class="container">
            <a class="navbar-brand logo" href="<?php echo BASE_URL; ?>welcome.php" id="logo">
                <i class="fas fa-cut me-2"></i>QuickCut
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" data-nav-target="hero" href="<?php echo BASE_URL; ?>welcome.php#hero">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-nav-target="services" href="<?php echo BASE_URL; ?>welcome.php#services">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $is_logged_in ? BASE_URL.'queue/queuestatus.php' : BASE_URL.'auth/login.php'; ?>">Queue</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>aboutus.php">About</a>
                    </li>
                    
                    <?php if ($is_logged_in): ?>
                        <!-- Notification Bell -->
                        <li class="nav-item">
                            <div class="notification-bell-wrapper nav-link" id="notification-bell-wrapper" style="cursor: pointer;">
                                <i class="fas fa-bell"></i>
                                <span class="notification-badge" id="notification-badge">0</span>
                                
                                <div class="notification-dropdown" id="notification-dropdown">
                                    <div class="notification-header d-flex justify-content-between align-items-center p-3 border-bottom">
                                        <h6 class="mb-0">Notifications</h6>
                                        <a href="#" class="mark-all-read small text-primary" id="mark-all-read-btn">Mark all</a>
                                    </div>
                                    <div class="notification-list" id="notification-list" style="max-height: 300px; overflow-y: auto;">
                                        <div class="p-4 text-center text-muted">
                                            <i class="fas fa-bell-slash d-block mb-2 fa-2x"></i>
                                            <p class="small mb-0">No new notifications</p>
                                        </div>
                                    </div>
                                    <div class="notification-footer p-2 text-center border-top">
                                        <a href="#" class="small text-primary">View All</a>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <!-- User Profile Dropdown -->
                        <li class="nav-item dropdown ms-lg-2">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="avatar-sm me-2 bg-accent text-dark rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-weight: 700; border-radius: 50%;">
                                    <?php echo strtoupper(substr($_SESSION['name'], 0, 1)); ?>
                                </div>
                                <span><?php echo explode(' ', $_SESSION['name'])[0]; ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 rounded-3" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item py-2" href="<?php echo BASE_URL; ?>profile/profile.php"><i class="fas fa-user-circle me-2"></i>My Profile</a></li>
                                <li><a class="dropdown-item py-2" href="<?php echo BASE_URL; ?>booking/bookappointment.php"><i class="fas fa-calendar-check me-2"></i>My Bookings</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item py-2 text-danger" href="<?php echo BASE_URL; ?>auth/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item ms-lg-3">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>auth/login.php">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
