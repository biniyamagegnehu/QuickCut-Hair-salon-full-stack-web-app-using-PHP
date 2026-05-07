<?php
// Google API Configuration
define('GOOGLE_CLIENT_ID', '659522094189-ps7o0objv9c5uokq76jk2to2c7hi3i6u.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', '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');
define('GOOGLE_REDIRECT_URL', 'http://localhost/QuickCut/auth/google_callback.php');

// Include Composer's autoloader
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Initialize Google Client
$client = new Google\Client();
$client->setClientId(GOOGLE_CLIENT_ID);
$client->setClientSecret(GOOGLE_CLIENT_SECRET);
$client->setRedirectUri(GOOGLE_REDIRECT_URL);
$client->addScope("email");
$client->addScope("profile");
?>
