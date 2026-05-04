<?php
// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'fleur_db';

try {
    $conn = new mysqli($host, $username, $password, $database);
    
    // Create magic_links table
    $result = $conn->query("SHOW TABLES LIKE 'magic_links'");
    
    if ($result->num_rows == 0) {
        $sql = "CREATE TABLE magic_links (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            user_id INT(11) NOT NULL,
            token VARCHAR(64) NOT NULL,
            expires_at DATETIME NOT NULL,
            created_at DATETIME NOT NULL,
            INDEX idx_token (token),
            INDEX idx_user_id (user_id),
            INDEX idx_expires_at (expires_at)
        )";
        
        if ($conn->query($sql)) {
            echo "Successfully created magic_links table.\n";
        } else {
            echo "Error creating magic_links table: " . $conn->error . "\n";
        }
    } else {
        echo "magic_links table already exists.\n";
    }
    
    // Create login_attempts table
    $result = $conn->query("SHOW TABLES LIKE 'login_attempts'");
    
    if ($result->num_rows == 0) {
        $sql = "CREATE TABLE login_attempts (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            ip_address VARCHAR(45) NOT NULL,
            created_at DATETIME NOT NULL,
            INDEX idx_ip_address (ip_address),
            INDEX idx_created_at (created_at)
        )";
        
        if ($conn->query($sql)) {
            echo "Successfully created login_attempts table.\n";
        } else {
            echo "Error creating login_attempts table: " . $conn->error . "\n";
        }
    } else {
        echo "login_attempts table already exists.\n";
    }
    
    // Add customer_email column to orders table if it doesn't exist
    $result = $conn->query("SHOW COLUMNS FROM orders LIKE 'customer_email'");
    
    if ($result->num_rows == 0) {
        $sql = "ALTER TABLE orders ADD COLUMN customer_email VARCHAR(255) NULL AFTER customer_id";
        
        if ($conn->query($sql)) {
            echo "Successfully added customer_email column to orders table.\n";
        } else {
            echo "Error adding customer_email column: " . $conn->error . "\n";
        }
    } else {
        echo "customer_email column already exists in orders table.\n";
    }
    
    $conn->close();
    
} catch (Exception $e) {
    echo "Database connection failed: " . $e->getMessage() . "\n";
}
?>
