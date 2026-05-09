<?php
session_start();
require_once '../includes/db.php';
require_once '../helpers/mail_helper.php';

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email address.";
        $message_type = "danger";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id, name, auth_provider FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && ($user['auth_provider'] === 'local' || empty($user['auth_provider']))) {
                // Generate reset token
                $reset_token = bin2hex(random_bytes(32));
                // Set expiry for 30 minutes
                $reset_token_expiry = date('Y-m-d H:i:s', strtotime('+30 minutes'));
                
                $update = $pdo->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE id = ?");
                
                if ($update->execute([$reset_token, $reset_token_expiry, $user['id']])) {
                    // Send Reset Email
                    $reset_link = "http://" . $_SERVER['HTTP_HOST'] . "/QuickCut/auth/reset-password.php?token=" . $reset_token;
                    $subject = "Password Reset Request";
                    $body = "<h2>Password Reset Request</h2>
                             <p>Hello {$user['name']},</p>
                             <p>We received a request to reset your password. Click the link below to set a new password:</p>
                             <p><a href='$reset_link'>$reset_link</a></p>
                             <p>This link will expire in 30 minutes.</p>
                             <p>If you did not request a password reset, please ignore this email.</p>";
                    
                    sendMail($email, $subject, $body);
                }
            }

            // Always show the same generic success message
            $message = "If the email exists and is a local account, a reset link has been sent.";
            $message_type = "success";
            
        } catch (PDOException $e) {
            $message = "Database error: " . $e->getMessage();
            $message_type = "danger";
        }
    }
}

$page_title = 'QuickCut - Forgot Password';
$additional_css = ['../assets/css/login.css'];
include '../includes/header.php';
?>

<main class="container mt-5 pt-5 min-vh-75 d-flex align-items-center justify-content-center">
    <div class="card login-card shadow-lg p-4 p-md-5" style="max-width: 500px; width: 100%;">
        <div class="text-center mb-4">
            <h2 class="fw-bold text-primary">Forgot Password?</h2>
            <p class="text-muted">Enter your email to receive a password reset link.</p>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="forgot-password.php" method="post">
            <div class="mb-4">
                <label for="email" class="form-label fw-semibold">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" name="email" id="email" class="form-control" placeholder="your@email.com" required>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary btn-lg w-100">
                <i class="fas fa-paper-plane me-2"></i>Send Reset Link
            </button>
            
            <div class="text-center mt-4 pt-3 border-top">
                <a href="login.php" class="text-decoration-none text-primary fw-semibold">
                    <i class="fas fa-arrow-left me-2"></i>Back to Login
                </a>
            </div>
        </form>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
