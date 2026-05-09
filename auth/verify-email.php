<?php
require_once '../includes/db.php';
session_start();

$message = "";
$success = false;

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    try {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE verification_token = ? AND is_verified = 0");
        $stmt->execute([$token]);
        $user = $stmt->fetch();

        if ($user) {
            $update = $pdo->prepare("UPDATE users SET is_verified = 1, verification_token = NULL WHERE id = ?");
            if ($update->execute([$user['id']])) {
                $message = "Email verified successfully! You can now log in.";
                $success = true;
            } else {
                $message = "Verification failed. Please try again later.";
            }
        } else {
            $message = "Invalid or expired verification link.";
        }
    } catch (PDOException $e) {
        $message = "Database error: " . $e->getMessage();
    }
} else {
    $message = "No verification token provided.";
}

$page_title = 'QuickCut - Email Verification';
$additional_css = ['../assets/css/login.css'];
include '../includes/header.php';
?>

<main class="container mt-5 pt-5 text-center min-vh-75 d-flex flex-column align-items-center justify-content-center">
    <div class="card login-card shadow-lg p-5" style="max-width: 500px; width: 100%;">
        <?php if ($success): ?>
            <div class="mb-4">
                <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
            </div>
            <h2 class="fw-bold text-success mb-3">Verified!</h2>
            <p class="text-muted mb-4"><?php echo htmlspecialchars($message); ?></p>
            <a href="login.php" class="btn btn-primary btn-lg w-100">Go to Login</a>
        <?php else: ?>
            <div class="mb-4">
                <i class="fas fa-times-circle text-danger" style="font-size: 4rem;"></i>
            </div>
            <h2 class="fw-bold text-danger mb-3">Verification Failed</h2>
            <p class="text-muted mb-4"><?php echo htmlspecialchars($message); ?></p>
            <a href="resend-verification.php" class="btn btn-outline-primary btn-lg w-100 mb-2">Resend Verification Email</a>
            <a href="login.php" class="btn btn-link text-decoration-none">Back to Login</a>
        <?php endif; ?>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
