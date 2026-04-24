<?php
require_once 'db.php';
$sql = file_get_contents('working_hours.sql');
try {
    $pdo->exec($sql);
    echo "Success";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
