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
    $stmt = $pdo->prepare("
        SELECT id, name, email, phone, profile_image, auth_provider, role, created_at 
        FROM users 
        WHERE id = ? AND role = 'customer'
    ");
    $stmt->execute([$customer_id]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($customer) {
        echo json_encode(['success' => true, 'customer' => $customer]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Customer not found']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
