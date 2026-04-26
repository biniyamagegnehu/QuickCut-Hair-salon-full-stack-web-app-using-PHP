<?php
require_once 'db.php';
$stmt = $pdo->prepare("DESCRIBE appointments");
$stmt->execute();
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
?>
