<?php
require_once 'db.php';

$date_today = date('Y-m-d');
echo "Current Server Date: $date_today\n\n";

$stmt = $pdo->prepare("SELECT a.id, a.user_id, u.name, a.appointment_date, a.status 
                       FROM appointments a 
                       JOIN users u ON a.user_id = u.id 
                       ORDER BY a.id DESC LIMIT 10");
$stmt->execute();
$apps = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Latest 10 Appointments in System:\n";
foreach ($apps as $app) {
    echo "ID: {$app['id']} | User: {$app['name']} (ID: {$app['user_id']}) | Date: {$app['appointment_date']} | Status: {$app['status']}\n";
}
?>
