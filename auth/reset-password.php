<?php
require_once '../includes/db.php';
session_start();

$message = "";
$is_valid_token = false;
$token = "";

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    try {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_token_expiry > NOW()");
        $stmt->execute([$token]);
        if ($stmt->fetch()) {
            $is_valid_token = true;
        } else {
            $message = "Reset link expired or invalid.";
        }
    } catch (PDOException $e) {
        $message = "Database error: " . $e->getMessage();
    }
} else {
    $message = "No reset token provided.";
}

$page_title = 'QuickCut - Reset Password';
$additional_css = ['../assets/css/login.css'];
include '../includes/header.php';
?>

<main class="container mt-5 pt-5 min-vh-75 d-flex flex-column align-items-center justify-content-center">
    <div class="card login-card shadow-lg p-4 p-md-5" style="max-width: 500px; width: 100%;">
        <div class="text-center mb-4">
            <h2 class="fw-bold text-primary">Reset Password</h2>
            <p class="text-muted">Create a new secure password.</p>
        </div>

        <?php if (!$is_valid_token): ?>
            <div class="alert alert-danger text-center" role="alert">
                <i class="fas fa-exclamation-circle fa-2x mb-2 d-block"></i>
                <?php echo htmlspecialchars($message); ?>
            </div>
            <div class="text-center mt-3">
                <a href="forgot-password.php" class="btn btn-outline-primary w-100">Request New Link</a>
                <a href="login.php" class="btn btn-link mt-2 text-decoration-none">Back to Login</a>
            </div>
        <?php else: ?>
            <form action="update-password.php" method="post" id="reset-password-form">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                
                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">New Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password" id="password" class="form-control" required minlength="6">
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="confirm_password" class="form-label fw-semibold">Confirm Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" required minlength="6">
                    </div>
                    <div class="invalid-feedback" id="password-match-error" style="display: none;">
                        Passwords do not match.
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary btn-lg w-100">
                    <i class="fas fa-save me-2"></i>Reset Password
                </button>
            </form>

            <script>
                document.getElementById('reset-password-form').addEventListener('submit', function(e) {
                    const password = document.getElementById('password').value;
                    const confirm = document.getElementById('confirm_password').value;
                    const errorMsg = document.getElementById('password-match-error');
                    
                    if (password !== confirm) {
                        e.preventDefault();
                        errorMsg.style.display = 'block';
                        document.getElementById('confirm_password').classList.add('is-invalid');
                    } else {
                        errorMsg.style.display = 'none';
                        document.getElementById('confirm_password').classList.remove('is-invalid');
                    }
                });
            </script>
        <?php endif; ?>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
