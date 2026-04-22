<?php
session_start();
require_once 'db.php';

header('Content-Type: application/json');

// To calculate queue and estimate waiting time
// For a user, we find their position in today's queue
$date_today = date('Y-m-d');

try {
    // Get all waiting and in_progress appointments today ordered by appointment_time and created_at
    $stmt = $pdo->prepare("
        SELECT a.id, a.user_id, s.duration, a.status 
        FROM appointments a 
        JOIN services s ON a.service_id = s.id 
        WHERE a.appointment_date = ? AND a.status IN ('waiting', 'in_progress') 
        ORDER BY a.appointment_time ASC, a.created_at ASC
    ");
    $stmt->execute([$date_today]);
    $queue = $stmt->fetchAll();
    
    $user_position = 0;
    $wait_time = 0;
    $found_user = false;
    
    $current_user_id = $_SESSION['user_id'] ?? null;
    $user_queue_status = null;

    $total_waiting = 0;
    
    foreach ($queue as $app) {
        if ($app['status'] == 'waiting') {
            $total_waiting++;
        }
        
        // If we found the current user's appointment
        if ($current_user_id && $app['user_id'] == $current_user_id && $app['status'] == 'waiting' && !$found_user) {
            $user_position = $total_waiting;
            $found_user = true;
            $user_queue_status = [
                'position' => $user_position,
                'estimated_wait_time_minutes' => $wait_time
            ];
        }
        
        // Accumulate wait time from people ahead of the user
        // (including 'in_progress' and 'waiting' before them)
        if (!$found_user && in_array($app['status'], ['waiting', 'in_progress'])) {
            $wait_time += $app['duration'];
        }
    }
    
    echo json_encode([
        'success' => true,
        'total_in_queue' => $total_waiting,
        'user_queue_info' => $user_queue_status
    ]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
