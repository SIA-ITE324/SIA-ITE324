<?php

// Simple database configuration for standalone version
class SimpleDatabase {
    private static $instance = null;
    private $connection;
    
    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $database = 'fleur_db';
    
    private function __construct() {
        try {
            $this->connection = new mysqli($this->host, $this->username, $this->password, $this->database);
            $this->connection->set_charset("utf8mb4");
        } catch (Exception $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    public function query($sql) {
        return $this->connection->query($sql);
    }
    
    public function escape($string) {
        return $this->connection->real_escape_string($string);
    }
    
    public function insertId() {
        return $this->connection->insert_id;
    }
    
    public function affectedRows() {
        return $this->connection->affected_rows;
    }
}
?>
