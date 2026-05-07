<?php
// Google API Configuration
define('GOOGLE_CLIENT_ID', 'YOUR_CLIENT_ID_HERE');
define('GOOGLE_CLIENT_SECRET', 'YOUR_CLIENT_SECRET_HERE');
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
