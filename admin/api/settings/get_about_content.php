<?php
require_once '../../admin_auth.php';
require_once '../../../includes/db.php';

check_admin();

header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT * FROM about_content LIMIT 1");
    $content = $stmt->fetch();

    if ($content) {
        echo json_encode(['success' => true, 'data' => $content]);
    } else {
        echo json_encode(['success' => false, 'message' => 'About content not found']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
