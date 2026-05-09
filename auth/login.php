<?php
session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: ../welcome.php");
    exit;
}

// Handle backend POST request for login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once '../includes/db.php';
    header('Content-Type: application/json');
    
    $input = json_decode(file_get_contents('php://input'), true);
    $email = isset($input['email']) ? trim($input['email']) : (isset($_POST['email']) ? trim($_POST['email']) : '');
    $password = isset($input['password']) ? $input['password'] : (isset($_POST['password']) ? $_POST['password'] : '');
    
    if (empty($email) || empty($password)) {
        echo json_encode(["success" => false, "message" => "Email and password are required."]);
        exit;
    }
    
    try {
        $stmt = $pdo->prepare("SELECT id, name, password, role, auth_provider, is_verified FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            if (($user['auth_provider'] === 'local' || empty($user['auth_provider'])) && $user['is_verified'] == 0) {
                echo json_encode(["success" => false, "message" => "Please verify your email before logging in."]);
                exit;
            }
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['auth_provider'] = $user['auth_provider'] ?? 'local';
            $_SESSION['email'] = $email;
            
            // Redirect based on role
            $redirect = ($user['role'] === 'admin') ? '../admin/dashboard.php' : '../welcome.php';
            
            echo json_encode([
                "success" => true, 
                "message" => "Login successful.", 
                "redirect" => $redirect,
                "role" => $user['role'],
                "name" => $user['name']
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Invalid email or password."]);
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
    exit;
}
?>
<?php
$page_title = 'QuickCut - Login';
$additional_css = ['../assets/css/login.css'];
include '../includes/header.php';
?>

    <!-- Main Content -->
    <main class="container mt-5 pt-5" id="login-main">
        <div class="row align-items-center min-vh-75">
            <!-- Login Form Column -->
            <div class="col-lg-6 mb-5 mb-lg-0" id="login-form-column">
                <div class="card login-card shadow-lg" id="login-card">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <h2 class="fw-bold text-primary" id="login-title">Welcome Back</h2>
                            <p class="text-muted" id="login-subtitle">Log in to manage your appointments</p>
                        </div>
                        
                        <form action="#" method="get" id="login-form">
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="email-icon">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" class="form-control" id="login-email" placeholder="your@email.com" required>
                                </div>
                                <div class="invalid-feedback" id="email-error"></div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="password-icon">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" class="form-control" id="login-password" required>
                                    <button type="button" class="btn btn-outline-secondary" id="toggle-password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="text-center mt-2">
                                    <a href="forgot-password.php" class="text-decoration-none text-primary forgot-password" id="forgot-password-link">
                                        Forgot Password?
                                    </a>
                                </div>
                                <div class="invalid-feedback" id="password-error"></div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-lg w-100 mt-3 login-btn" id="login-submit-btn">
                                <i class="fas fa-sign-in-alt me-2"></i>Log In
                            </button>
                            
                            <div class="divider d-flex align-items-center my-4">
                                <p class="text-center fw-bold mx-3 mb-0 text-muted">OR</p>
                            </div>

                            <a href="google_login.php" class="btn btn-outline-dark btn-lg w-100 google-btn" id="google-login-btn">
                                <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google" width="20" class="me-2">
                                Continue with Google
                            </a>
                            
                            <div class="text-center mt-3" id="login-message">
                                <?php if (isset($_SESSION['login_success'])): ?>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <?php 
                                            echo $_SESSION['login_success']; 
                                            unset($_SESSION['login_success']);
                                        ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>
                                <?php if (isset($_SESSION['login_error'])): ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <?php 
                                            echo $_SESSION['login_error']; 
                                            unset($_SESSION['login_error']);
                                        ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </form>
                        
                        <div class="text-center mt-4 pt-3 border-top" id="create-account-section">
                            <p class="text-muted mb-3" id="new-user-text">New to QuickCut?</p>
                            <a href="register.php" class="btn btn-outline-primary btn-lg w-100 create-account-btn" id="create-account-btn">
                                <i class="fas fa-user-plus me-2"></i>Create an Account
                            </a>
                        </div>
                        
                        <div class="text-center mt-4" id="terms-section">
                            <p class="small text-muted" id="terms-text">
                                By logging in, you agree to our 
                                <a href="#" class="text-decoration-none text-primary" id="terms-link">Terms of Service</a> 
                                and 
                                <a href="#" class="text-decoration-none text-primary" id="privacy-link">Privacy Policy</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Welcome Column -->
            <div class="col-lg-6" id="welcome-column">
                <div class="text-center text-lg-start">
                    <h1 class="display-4 fw-bold mb-3" id="welcome-title">
                        Skip the wait. 
                        <span class="text-primary">Get the cut.</span>
                    </h1>
                    <p class="lead text-muted mb-4" id="welcome-description">
                        Book your appointment online and avoid long queues. 
                        QuickCut connects you with the best barbers and stylists in your area.
                    </p>
                    
                    <div class="features mt-5" id="features-section">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="feature-card p-3 h-100" id="feature-1">
                                    <div class="feature-icon mb-3">
                                        <i class="fas fa-clock fa-2x text-primary"></i>
                                    </div>
                                    <h5 class="fw-bold" id="feature-1-title">Quick Booking</h5>
                                    <p class="text-muted small" id="feature-1-desc">Book appointments in under 2 minutes</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="feature-card p-3 h-100" id="feature-2">
                                    <div class="feature-icon mb-3">
                                        <i class="fas fa-calendar-check fa-2x text-primary"></i>
                                    </div>
                                    <h5 class="fw-bold" id="feature-2-title">Real-time Updates</h5>
                                    <p class="text-muted small" id="feature-2-desc">Live queue status and notifications</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="feature-card p-3 h-100" id="feature-3">
                                    <div class="feature-icon mb-3">
                                        <i class="fas fa-star fa-2x text-primary"></i>
                                    </div>
                                    <h5 class="fw-bold" id="feature-3-title">Top Professionals</h5>
                                    <p class="text-muted small" id="feature-3-desc">Rated and reviewed barbers & stylists</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="feature-card p-3 h-100" id="feature-4">
                                    <div class="feature-icon mb-3">
                                        <i class="fas fa-credit-card fa-2x text-primary"></i>
                                    </div>
                                    <h5 class="fw-bold" id="feature-4-title">Secure Payments</h5>
                                    <p class="text-muted small" id="feature-4-desc">Safe and easy online payments</p>
                                </div>
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
$additional_scripts = ['../assets/js/auth.js', '../assets/js/login.js'];
include '../includes/footer.php';
?>
