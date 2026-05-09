<?php
require_once 'admin_auth.php';
require_once '../includes/db.php';
check_admin();

header('Content-Type: application/json');

$id = $_POST['id'] ?? null;
$first_name = $_POST['firstName'] ?? '';
$last_name = $_POST['lastName'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$specialty = $_POST['specialty'] ?? '';
$rate = $_POST['rate'] ?? 0;
$status = $_POST['status'] ?? 'active';
$bio = $_POST['bio'] ?? '';
$experience = $_POST['experience'] ?? 0;

if (!$id || empty($first_name) || empty($last_name) || empty($email) || empty($phone)) {
    echo json_encode(['success' => false, 'message' => 'Invalid data or ID provided']);
    exit;
}

try {
    // Get current image to handle replacement
    $stmt = $pdo->prepare("SELECT profile_image FROM barbers WHERE id = ?");
    $stmt->execute([$id]);
    $current_barber = $stmt->fetch();
    $profile_image = $current_barber['profile_image'] ?? null;

    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/barbers/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_ext = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
        $filename = 'barber_' . time() . '_' . uniqid() . '.' . $file_ext;
        $target_file = $upload_dir . $filename;
        
        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
            // Delete old image if it exists
            if ($profile_image && file_exists('../' . $profile_image)) {
                unlink('../' . $profile_image);
            }
            $profile_image = 'uploads/barbers/' . $filename;
        }
    }

    $stmt = $pdo->prepare("UPDATE barbers SET first_name = ?, last_name = ?, email = ?, phone = ?, specialty = ?, rate = ?, status = ?, bio = ?, experience_years = ?, profile_image = ? WHERE id = ?");
    $stmt->execute([$first_name, $last_name, $email, $phone, $specialty, $rate, $status, $bio, $experience, $profile_image, $id]);
    echo json_encode(['success' => true, 'message' => 'Barber updated successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}

?>