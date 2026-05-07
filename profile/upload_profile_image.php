<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_image'])) {
    $file = $_FILES['profile_image'];
    $allowed_types = ['image/jpeg', 'image/png', 'image/webp'];
    $max_size = 2 * 1024 * 1024; // 2MB

    if (!in_array($file['type'], $allowed_types)) {
        echo json_encode(['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, and WEBP allowed.']);
        exit;
    }

    if ($file['size'] > $max_size) {
        echo json_encode(['success' => false, 'message' => 'File too large. Max 2MB allowed.']);
        exit;
    }

    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'profile_' . $_SESSION['user_id'] . '_' . time() . '.' . $extension;
    $upload_path = __DIR__ . '/../assets/uploads/profiles/' . $filename;
    $db_path = 'assets/uploads/profiles/' . $filename;

    if (move_uploaded_file($file['tmp_name'], $upload_path)) {
        try {
            // Delete old image if exists
            $stmt = $pdo->prepare("SELECT profile_image FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $old_image = $stmt->fetchColumn();
            
            if ($old_image && file_exists(__DIR__ . '/../' . $old_image)) {
                unlink(__DIR__ . '/../' . $old_image);
            }

            // Update database
            $update = $pdo->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
            $update->execute([$db_path, $_SESSION['user_id']]);

            echo json_encode([
                'success' => true, 
                'message' => 'Image uploaded successfully',
                'image_url' => BASE_URL . $db_path
            ]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to move uploaded file']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
