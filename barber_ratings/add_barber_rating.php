<?php
session_start();
require_once '../includes/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to rate']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$customer_id = $_SESSION['user_id'];
$barber_id = $data['barber_id'] ?? null;
$appointment_id = $data['appointment_id'] ?? null;
$rating = intval($data['rating'] ?? 0);
$review = trim($data['review'] ?? '');

if (!$barber_id || !$appointment_id || $rating < 1 || $rating > 5) {
    echo json_encode(['success' => false, 'message' => 'Invalid rating data']);
    exit;
}

try {
    // Verify appointment belongs to customer and is completed
    $stmt = $pdo->prepare("SELECT id FROM appointments WHERE id = ? AND user_id = ? AND status = 'completed'");
    $stmt->execute([$appointment_id, $customer_id]);
    if (!$stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Only completed appointments can be rated']);
        exit;
    }

    // Check for duplicate rating
    $stmt = $pdo->prepare("SELECT id FROM barber_ratings WHERE customer_id = ? AND appointment_id = ?");
    $stmt->execute([$customer_id, $appointment_id]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'You have already rated this appointment']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO barber_ratings (customer_id, barber_id, appointment_id, rating, review) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$customer_id, $barber_id, $appointment_id, $rating, $review]);

    echo json_encode(['success' => true, 'message' => 'Thank you for rating your barber!']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
