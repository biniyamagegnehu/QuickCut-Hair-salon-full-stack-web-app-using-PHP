<?php
require_once 'admin_auth.php';
require_once '../includes/db.php';
check_admin();

header('Content-Type: application/json');

try {
    $stmt = $pdo->query("
        SELECT b.*, 
               (SELECT COALESCE(AVG(rating), 0) FROM barber_ratings WHERE barber_id = b.id) as avg_rating,
               (SELECT COUNT(*) FROM barber_ratings WHERE barber_id = b.id) as review_count,
               (SELECT COUNT(*) FROM appointments WHERE barber_id = b.id) as appointments,
               (SELECT COALESCE(SUM(s.price), 0) 
                FROM appointments a 
                JOIN services s ON a.service_id = s.id 
                WHERE a.barber_id = b.id AND a.status = 'completed') as earnings
        FROM barbers b
        ORDER BY b.created_at DESC
    ");
    $barbers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'barbers' => $barbers]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
