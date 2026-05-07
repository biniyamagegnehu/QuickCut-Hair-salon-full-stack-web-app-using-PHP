<?php
require_once 'admin_auth.php';
require_once '../includes/config.php';
check_admin_page();

$customer_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($customer_id <= 0) {
    header("Location: customers.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuickCut - Customer Profile</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/customer_profile.css">
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-header">
            <a href="dashboard.php" class="sidebar-brand">
                <i class="fas fa-cut me-2"></i>QuickCut Admin
            </a>
        </div>
        
        <div class="sidebar-menu">
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="appointments.php"><i class="fas fa-calendar-alt"></i> Appointments</a></li>
                <li class="nav-item"><a class="nav-link" href="barbers.php"><i class="fas fa-user-tie"></i> Barbers</a></li>
                <li class="nav-item"><a class="nav-link" href="services.php"><i class="fas fa-cut"></i> Services</a></li>
                <li class="nav-item"><a class="nav-link" href="working_hours.php"><i class="fas fa-clock"></i> Working Hours</a></li>
                <li class="nav-item"><a class="nav-link active" href="customers.php"><i class="fas fa-users"></i> Customers</a></li>
                <li class="nav-item"><a class="nav-link" href="reports.php"><i class="fas fa-chart-bar"></i> Reports</a></li>
                <li class="nav-item"><a class="nav-link" href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
            </ul>
        </div>
        
        <div class="sidebar-footer">
            <div class="admin-profile">
                <div class="admin-avatar"><i class="fas fa-user"></i></div>
                <div class="admin-info">
                    <h6 class="mb-0">Admin User</h6>
                    <small class="text-muted">Administrator</small>
                </div>
                <a href="welcome.php" class="logout-btn ms-auto" title="Logout"><i class="fas fa-sign-out-alt"></i></a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Navigation -->
        <nav class="top-nav">
            <div class="d-flex align-items-center">
                <button class="sidebar-toggle me-3"><i class="fas fa-bars"></i></button>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="customers.php">Customers</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Customer Profile</li>
                    </ol>
                </nav>
            </div>
            <div class="top-nav-items">
                <div class="date-time">
                    <span id="current-date"></span>
                    <span id="current-time"></span>
                </div>
            </div>
        </nav>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Header Section -->
            <div class="customer-profile-header">
                <img src="../assets/images/default-avatar.png" id="customer-avatar" class="customer-avatar-lg" alt="Profile Image">
                <div class="customer-meta">
                    <h2 id="customer-name-heading">Customer Name</h2>
                    <div class="d-flex gap-2">
                        <span class="badge bg-primary" id="customer-auth">LOCAL</span>
                        <span class="badge bg-success">ACTIVE</span>
                    </div>
                </div>
                <div class="ms-auto d-flex gap-2">
                    <button class="btn btn-outline-primary" onclick="window.history.back()">
                        <i class="fas fa-arrow-left me-2"></i>Back to List
                    </button>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon blue"><i class="fas fa-calendar-check"></i></div>
                    <div class="stat-info">
                        <h3 id="total-bookings">0</h3>
                        <p>Total Bookings</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green"><i class="fas fa-wallet"></i></div>
                    <div class="stat-info">
                        <h3 id="total-spent">0 ETB</h3>
                        <p>Total Revenue</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon orange"><i class="fas fa-clock"></i></div>
                    <div class="stat-info">
                        <h3 id="completed-bookings">0</h3>
                        <p>Completed Visits</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon red"><i class="fas fa-times-circle"></i></div>
                    <div class="stat-info">
                        <h3 id="cancelled-bookings">0</h3>
                        <p>Cancelled</p>
                    </div>
                </div>
            </div>

            <div class="profile-grid">
                <!-- Sidebar Info -->
                <div class="profile-sidebar">
                    <div id="queue-status-container">
                        <!-- Queue status card loaded here -->
                    </div>

                    <div class="sidebar-card">
                        <h5 class="fw-bold mb-4 border-bottom pb-2">Basic Information</h5>
                        <div class="info-item">
                            <label>Full Name</label>
                            <p id="customer-name">-</p>
                        </div>
                        <div class="info-item">
                            <label>Email Address</label>
                            <p id="customer-email">-</p>
                        </div>
                        <div class="info-item">
                            <label>Phone Number</label>
                            <p id="customer-phone">-</p>
                        </div>
                        <div class="info-item">
                            <label>Member Since</label>
                            <p id="customer-created">-</p>
                        </div>
                    </div>
                </div>

                <!-- Main Content tabs -->
                <div class="profile-main">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-0">
                            <div class="profile-tabs-nav p-4 pb-0">
                                <button class="tab-btn active" onclick="switchTab('bookings')">Booking History</button>
                                <button class="tab-btn" onclick="switchTab('payments')">Payment History</button>
                            </div>

                            <div id="bookings-tab" class="tab-content p-4 pt-2">
                                <div class="table-responsive">
                                    <table class="table align-middle">
                                        <thead>
                                            <tr>
                                                <th>Service</th>
                                                <th>Date</th>
                                                <th>Time</th>
                                                <th>Status</th>
                                                <th>Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody id="bookings-list">
                                            <!-- Loaded via JS -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div id="payments-tab" class="tab-content p-4 pt-2" style="display: none;">
                                <div class="table-responsive">
                                    <table class="table align-middle">
                                        <thead>
                                            <tr>
                                                <th>Reference</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody id="payments-list">
                                            <!-- Loaded via JS -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- JavaScript Files -->
    <script src="js/common.js"></script>
    <script src="js/customer_profile.js"></script>
    <script>
        function switchTab(tab) {
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            
            if (tab === 'bookings') {
                document.getElementById('bookings-tab').style.display = 'block';
                document.getElementById('payments-tab').style.display = 'none';
            } else {
                document.getElementById('bookings-tab').style.display = 'none';
                document.getElementById('payments-tab').style.display = 'block';
            }
        }
    </script>
</body>
</html>
