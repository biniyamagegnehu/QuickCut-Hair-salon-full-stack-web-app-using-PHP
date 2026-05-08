<?php
require_once '../includes/db.php';

header('Content-Type: application/json');

$date = $_GET['date'] ?? '';
$barber_id = $_GET['barber_id'] ?? '';

if (empty($date)) {
    echo json_encode(['success' => false, 'message' => 'Date is required']);
    exit;
}

try {
    $sql = "SELECT appointment_time FROM appointments WHERE appointment_date = ? AND status IN ('waiting', 'scheduled', 'confirmed', 'in_progress')";
    $params = [$date];

    if (!empty($barber_id)) {
        $sql .= " AND barber_id = ?";
        $params[] = $barber_id;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $booked_slots = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode(['success' => true, 'booked_slots' => $booked_slots]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
