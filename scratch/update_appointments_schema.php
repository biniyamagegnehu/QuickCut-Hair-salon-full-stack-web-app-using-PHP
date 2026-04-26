<?php
require_once 'db.php';

try {
    // Check if barber_id exists
    $cols = $pdo->query("DESCRIBE appointments")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('barber_id', $cols)) {
        $pdo->exec("ALTER TABLE appointments ADD COLUMN barber_id INT AFTER service_id");
        echo "Added barber_id to appointments.\n";
        
        // Add foreign key if barbers table exists
        $pdo->exec("ALTER TABLE appointments ADD CONSTRAINT fk_barber FOREIGN KEY (barber_id) REFERENCES barbers(id)");
        echo "Added foreign key fk_barber.\n";
    } else {
        echo "barber_id already exists.\n";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
