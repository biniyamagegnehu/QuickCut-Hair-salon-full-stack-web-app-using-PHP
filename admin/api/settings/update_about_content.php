<?php
require_once '../../admin_auth.php';
require_once '../../../includes/db.php';

check_admin();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$about_title = $_POST['about_title'] ?? '';
$about_description = $_POST['about_description'] ?? '';
$mission = $_POST['mission'] ?? '';
$vision = $_POST['vision'] ?? '';
$story = $_POST['story'] ?? '';

if (empty($about_title)) {
    echo json_encode(['success' => false, 'message' => 'About title is required']);
    exit;
}

try {
    $image_path = null;
    if (isset($_FILES['about_image']) && $_FILES['about_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../../../assets/uploads/about/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_extension = pathinfo($_FILES['about_image']['name'], PATHINFO_EXTENSION);
        $file_name = 'about_' . time() . '.' . $file_extension;
        $target_file = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['about_image']['tmp_name'], $target_file)) {
            $image_path = 'assets/uploads/about/' . $file_name;
        }
    }

    if ($image_path) {
        $stmt = $pdo->prepare("UPDATE about_content SET 
            about_title = ?, about_description = ?, mission = ?, 
            vision = ?, story = ?, about_image = ? WHERE id = 1");
        $stmt->execute([$about_title, $about_description, $mission, $vision, $story, $image_path]);
    } else {
        $stmt = $pdo->prepare("UPDATE about_content SET 
            about_title = ?, about_description = ?, mission = ?, 
            vision = ?, story = ? WHERE id = 1");
        $stmt->execute([$about_title, $about_description, $mission, $vision, $story]);
    }

    echo json_encode(['success' => true, 'message' => 'About content updated successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
