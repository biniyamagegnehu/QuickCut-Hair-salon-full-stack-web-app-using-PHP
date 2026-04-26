<?php
function create_notification($pdo, $user_id, $message, $type) {
    try {
        $stmt = $pdo->prepare("INSERT INTO Notifications (user_id, message, type) VALUES (?, ?, ?)");
        return $stmt->execute([$user_id, $message, $type]);
    } catch (PDOException $e) {
        return false;
    }
}
?>
