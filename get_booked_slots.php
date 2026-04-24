<?php
require_once 'db.php';

header('Content-Type: application/json');

$date = $_GET['date'] ?? '';

if (empty($date)) {
    echo json_encode(['success' => false, 'message' => 'Date is required']);
    exit;
}

try {
    // Fetch booked slots for the given date
    // We only care about scheduled, confirmed, or in_progress appointments
    $stmt = $pdo->prepare("SELECT appointment_time FROM appointments WHERE appointment_date = ? AND status IN ('scheduled', 'confirmed', 'in_progress')");
    $stmt->execute([$date]);
    $booked_slots = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode(['success' => true, 'booked_slots' => $booked_slots]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
