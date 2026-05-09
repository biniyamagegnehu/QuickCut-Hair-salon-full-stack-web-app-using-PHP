<?php
require_once 'admin_auth.php';
require_once '../includes/db.php';
check_admin();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$name = $data['name'] ?? '';
$phone = $data['phone'] ?? '';
$service_id = $data['service_id'] ?? '';
$barber_id = $data['barber_id'] ?? '';
$date = $data['date'] ?? '';
$time = $data['time'] ?? '';

if (empty($name) || empty($service_id) || empty($barber_id) || empty($date) || empty($time)) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

try {
    // Check if slot is available
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE barber_id = ? AND appointment_date = ? AND appointment_time = ? AND status NOT IN ('cancelled', 'completed')");
    $stmt->execute([$barber_id, $date, $time]);
    $existing = $stmt->fetchColumn();

    if ($existing > 0) {
        echo json_encode(['success' => false, 'message' => 'Selected barber is unavailable for this time. Please choose another barber or time slot.']);
        exit;
    }

    // Generate queue number (fairness: last in line for the day)
    $stmt = $pdo->prepare("SELECT MAX(queue_number) FROM appointments WHERE appointment_date = ?");
    $stmt->execute([$date]);
    $max_queue = $stmt->fetchColumn();
    $queue_number = $max_queue ? $max_queue + 1 : 1;

    $stmt = $pdo->prepare("
        INSERT INTO appointments (
            service_id, barber_id, appointment_date, appointment_time, 
            status, appointment_source, walk_in_name, walk_in_phone, queue_number
        ) VALUES (?, ?, ?, ?, 'waiting', 'walk_in', ?, ?, ?)
    ");

    $stmt->execute([
        $service_id,
        $barber_id,
        $date,
        $time,
        $name,
        $phone,
        $queue_number
    ]);

    echo json_encode(['success' => true, 'message' => 'Walk-in customer added successfully to queue.', 'queue_number' => $queue_number]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
