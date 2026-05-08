<?php
require_once 'admin_auth.php';
require_once '../includes/db.php';
check_admin();

header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? null;

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'Invalid input data']);
    exit;
}

try {
    // Get image path before deleting
    $stmt = $pdo->prepare("SELECT image_path FROM services WHERE id = ?");
    $stmt->execute([$id]);
    $service = $stmt->fetch();
    
    if ($service && $service['image_path']) {
        $file_path = '../' . $service['image_path'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }

    $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
    $stmt->execute([$id]);
    
    echo json_encode(['success' => true, 'message' => 'Service deleted successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>

