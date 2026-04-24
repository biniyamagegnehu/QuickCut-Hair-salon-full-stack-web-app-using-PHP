<?php
session_start();
require_once 'db.php';
require_once 'create_notification.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);

$service_name = $data['service'] ?? '';
$date = $data['date'] ?? '';
$time = $data['time'] ?? '';

if (empty($service_name) || empty($date) || empty($time)) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
    exit;
}

// Convert 12-hour time format back to 24-hour if needed
$time_24 = date("H:i", strtotime($time));
$date_sql = date("Y-m-d", strtotime($date));

if (!$time_24 || !$date_sql) {
    echo json_encode(['success' => false, 'message' => 'Invalid date or time format.']);
    exit;
}

try {
    // Get service id
    $stmt = $pdo->prepare("SELECT id FROM services WHERE name = ?");
    $stmt->execute([strtolower($service_name)]);
    $service = $stmt->fetch();
    
    if (!$service) {
        echo json_encode(['success' => false, 'message' => 'Invalid service selected.']);
        exit;
    }
    
    $service_id = $service['id'];
    
    // Insert appointment
    $stmt = $pdo->prepare("INSERT INTO appointments (user_id, service_id, appointment_date, appointment_time, status) VALUES (?, ?, ?, ?, 'waiting')");
    $stmt->execute([$user_id, $service_id, $date_sql, $time_24]);
    
    $appointment_id = $pdo->lastInsertId();
    
    // Create a notification
    create_notification($pdo, $user_id, "Your appointment has been booked for $date_sql at $time_24.", "booking");
    
    echo json_encode([
        'success' => true, 
        'message' => 'Appointment booked successfully.', 
        'appointment_id' => $appointment_id
    ]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
