<?php
require_once 'admin_auth.php';
require_once '../includes/db.php';

check_admin();

header('Content-Type: application/json');

$customer_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($customer_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid customer ID']);
    exit;
}

try {
    // Get payment history
    $stmt = $pdo->prepare("
        SELECT id, amount, status, transaction_ref, created_at
        FROM payments
        WHERE user_id = ?
        ORDER BY created_at DESC
    ");
    $stmt->execute([$customer_id]);
    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get payment stats
    $stats_stmt = $pdo->prepare("
        SELECT 
            SUM(CASE WHEN status = 'success' THEN amount ELSE 0 END) as total_paid,
            SUM(CASE WHEN status = 'pending' THEN amount ELSE 0 END) as pending_amount,
            COUNT(CASE WHEN status = 'success' THEN 1 END) as successful_payments
        FROM payments
        WHERE user_id = ?
    ");
    $stats_stmt->execute([$customer_id]);
    $stats = $stats_stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true, 
        'payments' => $payments,
        'stats' => $stats
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
