<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($token) || empty($password) || empty($confirm_password)) {
        $_SESSION['login_error'] = "All fields are required.";
        header("Location: reset-password.php?token=" . urlencode($token));
        exit;
    }

    if ($password !== $confirm_password) {
        $_SESSION['login_error'] = "Passwords do not match.";
        header("Location: reset-password.php?token=" . urlencode($token));
        exit;
    }

    if (strlen($password) < 6) {
        $_SESSION['login_error'] = "Password must be at least 6 characters.";
        header("Location: reset-password.php?token=" . urlencode($token));
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_token_expiry > NOW()");
        $stmt->execute([$token]);
        $user = $stmt->fetch();

        if ($user) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $update = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE id = ?");
            
            if ($update->execute([$hashed_password, $user['id']])) {
                $_SESSION['login_success'] = "Password reset successfully. You can now log in.";
                header("Location: login.php");
                exit;
            } else {
                $_SESSION['login_error'] = "Failed to update password. Please try again.";
                header("Location: reset-password.php?token=" . urlencode($token));
                exit;
            }
        } else {
            $_SESSION['login_error'] = "Reset link expired or invalid.";
            header("Location: login.php");
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION['login_error'] = "Database error: " . $e->getMessage();
        header("Location: reset-password.php?token=" . urlencode($token));
        exit;
    }
} else {
    header("Location: login.php");
    exit;
}
