<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT a.id, s.name as service_name, a.appointment_date, a.appointment_time, a.status, s.price as amount,
               b.id as barber_id, CONCAT(b.first_name, ' ', b.last_name) as barber_name,
               r.id as rating_id
        FROM appointments a
        JOIN services s ON a.service_id = s.id
        LEFT JOIN barbers b ON a.barber_id = b.id
        LEFT JOIN barber_ratings r ON a.id = r.appointment_id
        WHERE a.user_id = ?
        ORDER BY a.appointment_date DESC, a.appointment_time DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'bookings' => $bookings]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
