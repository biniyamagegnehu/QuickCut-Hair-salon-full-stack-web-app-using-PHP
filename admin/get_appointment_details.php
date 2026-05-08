<?php
require_once 'admin_auth.php';

header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'Appointment ID is required']);
    exit;
}

$id = intval($_GET['id']);

try {
    // Get appointment with basic details
    $stmt = $pdo->prepare("
        SELECT a.*, 
               u.name as customer_name, u.phone as customer_phone, u.id as customer_id,
               s.name as service_name, s.price as service_price, s.duration as service_duration,
               b.first_name as barber_first, b.last_name as barber_last
        FROM appointments a
        JOIN users u ON a.user_id = u.id
        JOIN services s ON a.service_id = s.id
        LEFT JOIN barbers b ON a.barber_id = b.id
        WHERE a.id = ?
    ");
    $stmt->execute([$id]);
    $appointment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$appointment) {
        echo json_encode(['success' => false, 'message' => 'Appointment not found']);
        exit;
    }

    // Get payment details if they exist
    $stmt_pay = $pdo->prepare("SELECT * FROM payments WHERE appointment_id = ?");
    $stmt_pay->execute([$id]);
    $payment = $stmt_pay->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'appointment' => $appointment,
        'payment' => $payment ?: null
    ]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
