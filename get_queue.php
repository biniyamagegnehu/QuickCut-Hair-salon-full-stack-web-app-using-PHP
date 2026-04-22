<?php
session_start();
require_once 'db.php';

header('Content-Type: application/json');

$date_today = date('Y-m-d');

try {
    $stmt = $pdo->prepare("
        SELECT a.id, u.name as user_name, s.name as service_name, a.status, s.duration 
        FROM appointments a 
        JOIN users u ON a.user_id = u.id 
        JOIN services s ON a.service_id = s.id 
        WHERE a.appointment_date = ? AND a.status IN ('waiting', 'in_progress') 
        ORDER BY a.appointment_time ASC, a.created_at ASC
    ");
    $stmt->execute([$date_today]);
    $queue = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true,
        'queue' => $queue
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
