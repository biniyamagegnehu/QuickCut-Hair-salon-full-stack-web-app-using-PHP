<?php
require_once 'db.php';
$stmt = $pdo->prepare("DESCRIBE barbers");
$stmt->execute();
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
?>
