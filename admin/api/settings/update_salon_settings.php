<?php
require_once '../../admin_auth.php';
require_once '../../../includes/db.php';

check_admin();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$salon_name = $_POST['salon_name'] ?? '';
$tagline = $_POST['tagline'] ?? '';
$address = $_POST['address'] ?? '';
$phone = $_POST['phone'] ?? '';
$email = $_POST['email'] ?? '';
$facebook_link = $_POST['facebook_link'] ?? '';
$instagram_link = $_POST['instagram_link'] ?? '';
$telegram_link = $_POST['telegram_link'] ?? '';
$map_link = $_POST['map_link'] ?? '';

if (empty($salon_name)) {
    echo json_encode(['success' => false, 'message' => 'Salon name is required']);
    exit;
}

try {
    $logo_path = null;
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../../../assets/uploads/logos/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_extension = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
        $file_name = 'logo_' . time() . '.' . $file_extension;
        $target_file = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['logo']['tmp_name'], $target_file)) {
            $logo_path = 'assets/uploads/logos/' . $file_name;
        }
    }

    if ($logo_path) {
        $stmt = $pdo->prepare("UPDATE salon_settings SET 
            salon_name = ?, tagline = ?, address = ?, phone = ?, 
            email = ?, facebook_link = ?, instagram_link = ?, 
            telegram_link = ?, map_link = ?, logo = ? WHERE id = 1");
        $stmt->execute([$salon_name, $tagline, $address, $phone, $email, $facebook_link, $instagram_link, $telegram_link, $map_link, $logo_path]);
    } else {
        $stmt = $pdo->prepare("UPDATE salon_settings SET 
            salon_name = ?, tagline = ?, address = ?, phone = ?, 
            email = ?, facebook_link = ?, instagram_link = ?, 
            telegram_link = ?, map_link = ? WHERE id = 1");
        $stmt->execute([$salon_name, $tagline, $address, $phone, $email, $facebook_link, $instagram_link, $telegram_link, $map_link]);
    }

    echo json_encode(['success' => true, 'message' => 'Salon settings updated successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
