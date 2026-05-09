<?php
require_once '../config/google_config.php';
require_once '../includes/db.php';

session_start();

if (isset($_GET['code'])) {
    try {
        // Exchange authorization code for access token
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        
        if (isset($token['error'])) {
            throw new Exception("Error fetching access token: " . $token['error_description']);
        }
        
        $client->setAccessToken($token['access_token']);

        // Get user profile information from Google
        $google_oauth = new Google\Service\Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();
        
        $google_id = $google_account_info->id;
        $email = $google_account_info->email;
        $name = $google_account_info->name;

        // 1. Check if user exists with this google_id
        $stmt = $pdo->prepare("SELECT * FROM users WHERE google_id = ?");
        $stmt->execute([$google_id]);
        $user = $stmt->fetch();

        if (!$user) {
            // 2. Check if user exists with this email (maybe they registered normally before)
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user) {
                // Link Google ID to existing account
                $stmt = $pdo->prepare("UPDATE users SET google_id = ?, auth_provider = 'google', is_verified = 1 WHERE id = ?");
                $stmt->execute([$google_id, $user['id']]);
            } else {
                // 3. Create new user
                $stmt = $pdo->prepare("INSERT INTO users (name, email, google_id, auth_provider, role, is_verified) VALUES (?, ?, ?, 'google', 'customer', 1)");
                $stmt->execute([$name, $email, $google_id]);
                
                $user_id = $pdo->lastInsertId();
                
                // Fetch the newly created user
                $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
                $stmt->execute([$user_id]);
                $user = $stmt->fetch();
            }
        }

        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['auth_provider'] = $user['auth_provider'] ?? 'google';

        // Redirect based on role
        if ($user['role'] === 'admin') {
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: ../welcome.php");
        }
        exit;

    } catch (Exception $e) {
        $_SESSION['login_error'] = "Google Authentication failed: " . $e->getMessage();
        header("Location: login.php");
        exit;
    }
} else {
    header("Location: login.php");
    exit;
}
?>
