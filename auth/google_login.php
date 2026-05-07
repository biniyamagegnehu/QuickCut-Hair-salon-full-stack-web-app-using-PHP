<?php
require_once '../config/google_config.php';

// If already logged in, redirect to home
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: ../welcome.php");
    exit;
}

// Generate the URL to Google's OAuth 2.0 server
$auth_url = $client->createAuthUrl();

// Redirect the user to Google's OAuth 2.0 server
header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
exit;
?>
