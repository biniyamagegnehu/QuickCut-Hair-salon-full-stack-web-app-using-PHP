<?php
require_once '../../admin_auth.php';
require_once '../../../includes/db.php';

check_admin();

header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT * FROM salon_settings LIMIT 1");
    $settings = $stmt->fetch();

    if ($settings) {
        echo json_encode(['success' => true, 'data' => $settings]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Settings not found']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
