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
    // Get active appointment (waiting or confirmed for today)
    $stmt = $pdo->prepare("
        SELECT a.id, s.name as service_name, a.appointment_time, a.status, a.appointment_date
        FROM appointments a
        JOIN services s ON a.service_id = s.id
        WHERE a.user_id = ? AND a.appointment_date = CURDATE() AND a.status IN ('waiting', 'confirmed', 'in-progress')
        LIMIT 1
    ");
    $stmt->execute([$customer_id]);
    $active_appointment = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($active_appointment) {
        // Calculate queue position
        $pos_stmt = $pdo->prepare("
            SELECT COUNT(*) + 1 as position
            FROM appointments
            WHERE appointment_date = CURDATE() 
            AND status IN ('waiting', 'confirmed') 
            AND (appointment_time < ? OR (appointment_time = ? AND id < ?))
        ");
        $pos_stmt->execute([
            $active_appointment['appointment_time'], 
            $active_appointment['appointment_time'], 
            $active_appointment['id']
        ]);
        $queue_info = $pos_stmt->fetch(PDO::FETCH_ASSOC);
        
        $active_appointment['queue_position'] = $queue_info['position'];
        $active_appointment['est_wait_time'] = ($queue_info['position'] - 1) * 20; // Assume 20 mins per service

        echo json_encode(['success' => true, 'active' => true, 'appointment' => $active_appointment]);
    } else {
        echo json_encode(['success' => true, 'active' => false]);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
