<?php
require_once '../../admin_auth.php';
require_once '../../../includes/db.php';

check_admin();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$admin_id = $_SESSION['user_id'];
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';

if (empty($name) || empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Name and Email are required']);
    exit;
}

try {
    // Check if email is already taken by another user
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $stmt->execute([$email, $admin_id]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Email is already in use']);
        exit;
    }

    $image_path = null;
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../../../assets/uploads/profiles/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_extension = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
        $file_name = 'admin_' . $admin_id . '_' . time() . '.' . $file_extension;
        $target_file = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
            $image_path = 'assets/uploads/profiles/' . $file_name;
        }
    }

    if ($image_path) {
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, phone = ?, profile_image = ? WHERE id = ?");
        $stmt->execute([$name, $email, $phone, $image_path, $admin_id]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?");
        $stmt->execute([$name, $email, $phone, $admin_id]);
    }

    // Update session info
    $_SESSION['name'] = $name;
    $_SESSION['email'] = $email;

    echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
