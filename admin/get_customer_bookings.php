<?php
require_once 'admin_auth.php';
require_once '../includes/db.php';

check_admin();

header('Content-Type: application/json');

$customer_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($customer_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid customer ID']);
    exit;
}

try {
    // Get booking history
    $stmt = $pdo->prepare("
        SELECT a.id, s.name as service_name, a.appointment_date, a.appointment_time, a.status, s.price
        FROM appointments a
        JOIN services s ON a.service_id = s.id
        WHERE a.user_id = ?
        ORDER BY a.appointment_date DESC, a.appointment_time DESC
    ");
    $stmt->execute([$customer_id]);
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get stats
    $stats_stmt = $pdo->prepare("
        SELECT 
            COUNT(*) as total,
            COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed,
            COUNT(CASE WHEN status = 'waiting' OR status = 'confirmed' THEN 1 END) as upcoming,
            COUNT(CASE WHEN status = 'cancelled' THEN 1 END) as cancelled
        FROM appointments
        WHERE user_id = ?
    ");
    $stats_stmt->execute([$customer_id]);
    $stats = $stats_stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true, 
        'bookings' => $bookings,
        'stats' => $stats
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
