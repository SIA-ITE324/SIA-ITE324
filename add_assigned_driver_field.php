<?php
// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'fleur_db';

try {
    $conn = new mysqli($host, $username, $password, $database);
    
    // Check if assigned_driver column already exists
    $result = $conn->query("SHOW COLUMNS FROM orders LIKE 'assigned_driver'");
    
    if ($result->num_rows == 0) {
        // Add the assigned_driver column
        $sql = "ALTER TABLE orders ADD COLUMN assigned_driver VARCHAR(100) NULL AFTER actual_delivery";
        
        if ($conn->query($sql)) {
            echo "Successfully added assigned_driver column to orders table.\n";
        } else {
            echo "Error adding assigned_driver column: " . $conn->error . "\n";
        }
    } else {
        echo "assigned_driver column already exists in orders table.\n";
    }
    
    // Check if activity_logs table exists
    $result = $conn->query("SHOW TABLES LIKE 'activity_logs'");
    
    if ($result->num_rows == 0) {
        // Create activity_logs table
        $sql = "CREATE TABLE activity_logs (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            user_id INT(11) NULL,
            action VARCHAR(100) NOT NULL,
            entity_type VARCHAR(50) NOT NULL,
            entity_id INT(11) NOT NULL,
            description TEXT NULL,
            ip_address VARCHAR(45) NULL,
            user_agent TEXT NULL,
            created_at DATETIME NOT NULL,
            INDEX idx_user_entity (user_id, entity_type, entity_id),
            INDEX idx_created_at (created_at)
        )";
        
        if ($conn->query($sql)) {
            echo "Successfully created activity_logs table.\n";
        } else {
            echo "Error creating activity_logs table: " . $conn->error . "\n";
        }
    } else {
        echo "activity_logs table already exists.\n";
    }
    
    $conn->close();
    
} catch (Exception $e) {
    echo "Database connection failed: " . $e->getMessage() . "\n";
}
?>
