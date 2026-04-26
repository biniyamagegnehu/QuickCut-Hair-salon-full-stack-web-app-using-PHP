<?php
require_once 'db.php';

try {
    $sql = "
    CREATE TABLE IF NOT EXISTS payments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        appointment_id INT NOT NULL,
        amount DECIMAL(10,2) NOT NULL,
        status ENUM('pending','paid','failed') DEFAULT 'pending',
        transaction_ref VARCHAR(100) UNIQUE NOT NULL,
        chapa_response TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id),
        FOREIGN KEY (appointment_id) REFERENCES appointments(id)
    );
    ";
    
    $pdo->exec($sql);
    echo "Payments table created successfully.\n";

    // Also ensure appointments has status 'pending' and 'cancelled' in its ENUM if it's restricted, 
    // or just assume VARCHAR for now. Usually it's ENUM.
    // Let's check existing structure if possible, or just try to alter.
    
    // Most likely status is ENUM('waiting', 'scheduled', 'confirmed', 'in_progress', 'completed', 'cancelled')
    // We need to add 'pending' for the initial state before payment.
    
    // To be safe, we'll try to add 'pending' to the status column.
    // This depends on the existing definition. Let's try a generic approach.
    $pdo->exec("ALTER TABLE appointments MODIFY COLUMN status VARCHAR(50) DEFAULT 'waiting'");
    echo "Appointments status column updated to VARCHAR for flexibility.\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
