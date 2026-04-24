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
$phone = $data['phone'] ?? '';

if (empty($service_name) || empty($date) || empty($time)) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
    exit;
}

// Update user phone if provided and not already set
if (!empty($phone)) {
    $stmt = $pdo->prepare("UPDATE users SET phone = ? WHERE id = ? AND (phone IS NULL OR phone = '')");
    $stmt->execute([$phone, $user_id]);
}

// Convert 12-hour time format back to 24-hour if needed
$time_24 = date("H:i", strtotime($time));
$date_sql = date("Y-m-d", strtotime($date));

// 1. Get day of week for the requested date
$day_of_week = date('l', strtotime($date_sql));

// 2. Fetch working hours for that day
$stmt = $pdo->prepare("SELECT * FROM working_hours WHERE day_of_week = ?");
$stmt->execute([$day_of_week]);
$working_hours = $stmt->fetch();

if (!$working_hours) {
    echo json_encode(['success' => false, 'message' => 'Working hours not configured for this day.']);
    exit;
}

if ($working_hours['is_closed']) {
    echo json_encode(['success' => false, 'message' => "The salon is closed on $day_of_week."]);
    exit;
}

$open_time = $working_hours['open_time'];
$close_time = $working_hours['close_time'];

// Check if selected time is within range
if ($time_24 < $open_time || $time_24 >= $close_time) {
    echo json_encode(['success' => false, 'message' => "Selected time is outside working hours ($open_time - $close_time)."]);
    exit;
}

// Prevent duplicate bookings for the same slot
$stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE appointment_date = ? AND appointment_time = ? AND status IN ('waiting', 'scheduled', 'confirmed', 'in_progress')");
$stmt->execute([$date_sql, $time_24]);

if ((int)$stmt->fetchColumn() > 0) {
    echo json_encode(['success' => false, 'message' => 'That time slot has already been booked. Please choose another time.']);
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
