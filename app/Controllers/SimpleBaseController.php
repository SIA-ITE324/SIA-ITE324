<?php

// Simple base controller for standalone version
class SimpleBaseController {
    
    protected $db;
    
    public function __construct() {
        $this->db = SimpleDatabase::getInstance();
    }
    
    protected function view($viewFile, $data = []) {
        $viewPath = APPPATH . 'Views/' . $viewFile . '.php';
        
        if (file_exists($viewPath)) {
            extract($data);
            ob_start();
            include $viewPath;
            return ob_get_clean();
        } else {
            return "View file not found: " . $viewFile;
        }
    }
    
    protected function redirect($url) {
        header("Location: " . $url);
        exit;
    }
    
    protected function isLoggedIn() {
        return isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;
    }
    
    protected function getCurrentUserId() {
        return $_SESSION['user_id'] ?? null;
    }
    
    protected function getCurrentUserRole() {
        return $_SESSION['user_role'] ?? null;
    }
    
    protected function requireAuth() {
        if (!$this->isLoggedIn()) {
            $this->redirect(BASE_URL . 'login');
        }
    }
    
    protected function requireRole($role) {
        $this->requireAuth();
        if ($this->getCurrentUserRole() !== $role) {
            $this->redirect(BASE_URL . 'login');
        }
    }
    
    protected function validate($data, $rules) {
        $errors = [];
        
        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? '';
            
            if (strpos($rule, 'required') !== false && empty($value)) {
                $errors[$field] = ucfirst($field) . ' is required';
            }
            
            if (strpos($rule, 'email') !== false && !empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $errors[$field] = 'Please enter a valid email address';
            }
            
            if (strpos($rule, 'min_length') !== false && !empty($value)) {
                preg_match('/min_length\[(\d+)\]/', $rule, $matches);
                $minLength = $matches[1] ?? 0;
                if (strlen($value) < $minLength) {
                    $errors[$field] = ucfirst($field) . ' must be at least ' . $minLength . ' characters long';
                }
            }
        }
        
        return $errors;
    }
    
    protected function formatCurrency($amount) {
        return '$' . number_format($amount, 2);
    }
    
    protected function formatDate($date) {
        if (!$date) return 'N/A';
        return date('M d, Y', strtotime($date));
    }
}
?>
