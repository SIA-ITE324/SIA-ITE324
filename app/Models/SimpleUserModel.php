<?php

// Simple user model for standalone version
class SimpleUserModel {
    
    private $db;
    
    public function __construct() {
        $this->db = SimpleDatabase::getInstance();
    }
    
    public function getUserByEmail($email) {
        $email = $this->db->escape($email);
        $result = $this->db->query("SELECT * FROM users WHERE email = '$email'");
        return $result->fetch_assoc();
    }
    
    public function verifyPassword($email, $password) {
        $user = $this->getUserByEmail($email);
        
        if (!$user) {
            return false;
        }
        
        return password_verify($password, $user['password']);
    }
    
    public function createUser($data) {
        $firstName = $this->db->escape($data['first_name']);
        $lastName = $this->db->escape($data['last_name']);
        $email = $this->db->escape($data['email']);
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
        $role = $this->db->escape($data['role'] ?? 'customer');
        $status = $this->db->escape($data['status'] ?? 'active');
        
        $sql = "INSERT INTO users (first_name, last_name, email, password, role, status, created_at, updated_at) 
                VALUES ('$firstName', '$lastName', '$email', '$password', '$role', '$status', NOW(), NOW())";
        
        $this->db->query($sql);
        return $this->db->insertId();
    }
    
    public function updateLastLogin($userId) {
        $userId = (int)$userId;
        $this->db->query("UPDATE users SET last_login = NOW() WHERE id = $userId");
    }
    
    public function getUserById($userId) {
        $userId = (int)$userId;
        $result = $this->db->query("SELECT * FROM users WHERE id = $userId");
        return $result->fetch_assoc();
    }
    
    public function getAllUsers($role = null) {
        $sql = "SELECT * FROM users";
        if ($role) {
            $role = $this->db->escape($role);
            $sql .= " WHERE role = '$role'";
        }
        $sql .= " ORDER BY created_at DESC";
        
        $result = $this->db->query($sql);
        $users = [];
        
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        
        return $users;
    }
    
    public function updateUser($userId, $data) {
        $userId = (int)$userId;
        $setClause = [];
        
        if (isset($data['first_name'])) {
            $firstName = $this->db->escape($data['first_name']);
            $setClause[] = "first_name = '$firstName'";
        }
        
        if (isset($data['last_name'])) {
            $lastName = $this->db->escape($data['last_name']);
            $setClause[] = "last_name = '$lastName'";
        }
        
        if (isset($data['phone'])) {
            $phone = $this->db->escape($data['phone']);
            $setClause[] = "phone = '$phone'";
        }
        
        if (isset($data['address'])) {
            $address = $this->db->escape($data['address']);
            $setClause[] = "address = '$address'";
        }
        
        if (isset($data['status'])) {
            $status = $this->db->escape($data['status']);
            $setClause[] = "status = '$status'";
        }
        
        if (!empty($setClause)) {
            $setClause[] = "updated_at = NOW()";
            $sql = "UPDATE users SET " . implode(', ', $setClause) . " WHERE id = $userId";
            $this->db->query($sql);
            return $this->db->affectedRows() > 0;
        }
        
        return false;
    }
}
?>
