<?php
require_once '../includes/db.php';

header('Content-Type: application/json');

$barber_id = $_GET['barber_id'] ?? null;

if (!$barber_id) {
    echo json_encode(['success' => false, 'message' => 'Barber ID required']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT r.*, u.name as customer_name 
        FROM barber_ratings r
        JOIN users u ON r.customer_id = u.id
        WHERE r.barber_id = ?
        ORDER BY r.created_at DESC
    ");
    $stmt->execute([$barber_id]);
    $ratings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("
        SELECT AVG(rating) as avg_rating, COUNT(*) as review_count 
        FROM barber_ratings 
        WHERE barber_id = ?
    ");
    $stmt->execute([$barber_id]);
    $stats = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true, 
        'ratings' => $ratings,
        'avg_rating' => round($stats['avg_rating'], 1),
        'review_count' => $stats['review_count']
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
