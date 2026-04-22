<?php
session_start();

function check_admin() {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Unauthorized: Admin access required']);
        exit;
    }
}
?>
