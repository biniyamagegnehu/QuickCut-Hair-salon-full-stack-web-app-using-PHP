<?php
require_once 'admin_auth.php';
require_once '../includes/db.php';
check_admin();

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$appointment_id = $data['appointment_id'] ?? null;
$new_status = $data['status'] ?? '';

$valid_statuses = ['waiting', 'in_progress', 'completed', 'cancelled'];

if (!$appointment_id || !in_array($new_status, $valid_statuses)) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE appointments SET status = ? WHERE id = ?");
    $stmt->execute([$new_status, $appointment_id]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Appointment not found or status already set.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
