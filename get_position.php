<?php
session_start();
require_once 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];
$date_today = date('Y-m-d');

try {
    $stmt = $pdo->prepare("
        SELECT a.id, a.user_id, s.duration, a.status 
        FROM appointments a 
        JOIN services s ON a.service_id = s.id 
        WHERE a.appointment_date = ? AND a.status IN ('waiting', 'in_progress') 
        ORDER BY a.appointment_time ASC, a.created_at ASC
    ");
    $stmt->execute([$date_today]);
    $queue = $stmt->fetchAll();
    
    $position = 0;
    $people_ahead = 0;
    $wait_time = 0;
    $found_user = false;
    
    foreach ($queue as $index => $app) {
        if ($app['user_id'] == $user_id && $app['status'] == 'waiting') {
            $position = $index + 1;
            $people_ahead = $index;
            $found_user = true;
            break;
        }
        // Accumulate wait time from people ahead of the user
        $wait_time += $app['duration'];
    }
    
    if ($found_user) {
        if ($position <= 2) {
            $check_stmt = $pdo->prepare("SELECT id FROM Notifications WHERE user_id = ? AND type = 'queue' AND DATE(created_at) = CURDATE()");
            $check_stmt->execute([$user_id]);
            if ($check_stmt->rowCount() == 0) {
                require_once 'create_notification.php';
                create_notification($pdo, $user_id, "You are next. Please get ready.", "queue");
            }
        }
        echo json_encode([
            'success' => true,
            'position' => $position,
            'people_ahead' => $people_ahead,
            'estimated_wait_time_minutes' => $wait_time
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'User is not currently waiting in the queue.'
        ]);
    }

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
