<?php
require_once 'admin_auth.php';
require_once '../includes/db.php';
check_admin();

header('Content-Type: application/json');

$id = $_POST['id'] ?? null;
$name = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');
$duration = intval($_POST['duration'] ?? 0);
$price = floatval($_POST['price'] ?? 0);
$status = trim($_POST['status'] ?? 'active');

if (!$id || empty($name) || $duration <= 0 || $price < 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid input data']);
    exit;
}

try {
    // Get existing service to handle image deletion
    $stmt = $pdo->prepare("SELECT image_path FROM services WHERE id = ?");
    $stmt->execute([$id]);
    $service = $stmt->fetch();
    
    $image_path = $service['image_path'] ?? null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/services/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array(strtolower($file_extension), $allowed_extensions)) {
            echo json_encode(['success' => false, 'message' => 'Invalid file type']);
            exit;
        }

        $new_filename = 'service_' . time() . '_' . uniqid() . '.' . $file_extension;
        $target_path = $upload_dir . $new_filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
            // Delete old image if exists
            if ($image_path && file_exists('../' . $image_path)) {
                unlink('../' . $image_path);
            }
            $image_path = 'uploads/services/' . $new_filename;
        }
    }

    $stmt = $pdo->prepare("UPDATE services SET name = ?, description = ?, duration = ?, price = ?, status = ?, image_path = ? WHERE id = ?");
    $stmt->execute([$name, $description, $duration, $price, $status, $image_path, $id]);
    
    echo json_encode(['success' => true, 'message' => 'Service updated successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>

