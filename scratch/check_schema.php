<?php
require_once 'db.php';
$cols = $pdo->query("DESCRIBE appointments")->fetchAll(PDO::FETCH_COLUMN);
print_r($cols);
?>
