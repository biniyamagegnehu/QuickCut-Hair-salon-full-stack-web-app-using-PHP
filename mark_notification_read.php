<?php
session_start();
require_once 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = json_decode(file_get_contents('php://input'), true);
    $notification_id = isset($input['notification_id']) ? $input['notification_id'] : (isset($_POST['notification_id']) ? $_POST['notification_id'] : null);

    if (!$notification_id) {
        echo json_encode(["success" => false, "message" => "Notification ID is required"]);
        exit;
    }

    $user_id = $_SESSION['user_id'];

    try {
        // Ensure the notification belongs to the user
        $stmt = $pdo->prepare("UPDATE Notifications SET is_read = 1 WHERE id = ? AND user_id = ?");
        $stmt->execute([$notification_id, $user_id]);
        
        echo json_encode(["success" => true, "message" => "Notification marked as read"]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Database error"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}
?>
