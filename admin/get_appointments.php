<?php
session_start();
require_once '../db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$date_filter = $_GET['date'] ?? date('Y-m-d');
$status_filter = $_GET['status'] ?? '';

try {
    $sql = "SELECT a.id, u.name as customer_name, s.name as service_name, a.appointment_date, a.appointment_time, a.status, a.created_at 
            FROM appointments a 
            JOIN users u ON a.user_id = u.id 
            JOIN services s ON a.service_id = s.id 
            WHERE a.appointment_date = ?";
    
    $params = [$date_filter];
    
    if (!empty($status_filter)) {
        $sql .= " AND a.status = ?";
        $params[] = $status_filter;
    }
    
    $sql .= " ORDER BY a.appointment_time ASC, a.created_at ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $appointments = $stmt->fetchAll();
    
    echo json_encode(['success' => true, 'appointments' => $appointments]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
