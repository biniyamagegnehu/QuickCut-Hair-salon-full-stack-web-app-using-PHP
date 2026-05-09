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
            $stmt = $pdo->prepare("SELECT id, name, is_verified, auth_provider FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user) {
                if ($user['auth_provider'] !== 'local' && !empty($user['auth_provider'])) {
                    $message = "This email is registered with Google. No verification needed.";
                    $message_type = "info";
                } elseif ($user['is_verified'] == 1) {
                    $message = "This email is already verified. You can log in.";
                    $message_type = "info";
                } else {
                    // Generate new token
                    $verification_token = bin2hex(random_bytes(32));
                    $update = $pdo->prepare("UPDATE users SET verification_token = ? WHERE id = ?");
                    
                    if ($update->execute([$verification_token, $user['id']])) {
                        // Send Verification Email
                        $verify_link = "http://" . $_SERVER['HTTP_HOST'] . "/QuickCut/auth/verify-email.php?token=" . $verification_token;
                        $subject = "Verify Your QuickCut Account";
                        $body = "<h2>Welcome to QuickCut, {$user['name']}!</h2>
                                 <p>Please click the link below to verify your email address:</p>
                                 <p><a href='$verify_link'>$verify_link</a></p>
                                 <p>If you did not register for this account, please ignore this email.</p>";
                        
                        sendMail($email, $subject, $body);

                        $message = "A new verification link has been sent to your email.";
                        $message_type = "success";
                    } else {
                        $message = "Failed to generate a new verification link. Please try again.";
                        $message_type = "danger";
                    }
                }
            } else {
                // Generic message to prevent email enumeration
                $message = "If this email is registered and not verified, a verification link has been sent.";
                $message_type = "success";
            }
        } catch (PDOException $e) {
            $message = "Database error: " . $e->getMessage();
            $message_type = "danger";
        }
    }
}

$page_title = 'QuickCut - Resend Verification';
$additional_css = ['../assets/css/login.css'];
include '../includes/header.php';
?>

<main class="container mt-5 pt-5 min-vh-75 d-flex align-items-center justify-content-center">
    <div class="card login-card shadow-lg p-4 p-md-5" style="max-width: 500px; width: 100%;">
        <div class="text-center mb-4">
            <h2 class="fw-bold text-primary">Resend Verification</h2>
            <p class="text-muted">Enter your email to receive a new verification link.</p>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="resend-verification.php" method="post">
            <div class="mb-4">
                <label for="email" class="form-label fw-semibold">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" name="email" id="email" class="form-control" placeholder="your@email.com" required>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary btn-lg w-100">
                <i class="fas fa-paper-plane me-2"></i>Send Link
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
