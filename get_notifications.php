<?php
session_start();
require_once 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM Notifications WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    $notifications = $stmt->fetchAll();
    
    echo json_encode(["success" => true, "notifications" => $notifications]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error"]);
}
?>
