<?php

// Simple standalone Flower Order Management System
session_start();

// Define constants
define('BASE_URL', 'http://localhost/fleur/');
define('APPPATH', __DIR__ . '/app/');
define('VIEWPATH', APPPATH . 'Views/');

// Database configuration
class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        try {
            $this->connection = new mysqli('localhost', 'root', '', 'fleur_db');
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
    
    public function query($sql) {
        return $this->connection->query($sql);
    }
    
    public function escape($string) {
        return $this->connection->real_escape_string($string);
    }
    
    public function insertId() {
        return $this->connection->insert_id;
    }
}

// Base Controller
class Controller {
    protected $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    protected function view($viewFile, $data = []) {
        $viewPath = VIEWPATH . $viewFile . '.php';
        
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
    
    protected function requireAuth() {
        if (!$this->isLoggedIn()) {
            $this->redirect(BASE_URL . 'simple.php?action=login');
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
        }
        
        return $errors;
    }
}

// User Model
class UserModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
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
    
    public function updateLastLogin($userId) {
        $userId = (int)$userId;
        $this->db->query("UPDATE users SET last_login = NOW() WHERE id = $userId");
    }
}

// Home Controller
class HomeController extends Controller {
    public function index() {
        // Get featured products
        $result = $this->db->query("SELECT * FROM products WHERE is_featured = 1 AND status = 'active' LIMIT 4");
        $featuredProducts = [];
        
        while ($row = $result->fetch_assoc()) {
            $featuredProducts[] = $row;
        }
        
        $data = [
            'featured_products' => $featuredProducts,
            'page_title' => 'Welcome to Fleur - Flower Order Management System',
            'meta_description' => 'Beautiful flowers and arrangements for every occasion.',
        ];
        
        return $this->view('home/index', $data);
    }
}

// Auth Controller
class AuthController extends Controller {
    private $userModel;
    
    public function __construct() {
        parent::__construct();
        $this->userModel = new UserModel();
    }
    
    public function login() {
        if ($this->isLoggedIn()) {
            $this->redirect(BASE_URL . 'simple.php');
        }
        
        $data = ['page_title' => 'Login - Fleur'];
        return $this->view('auth/login', $data);
    }
    
    public function attemptLogin() {
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];
        
        $data = $_POST;
        $errors = $this->validate($data, $rules);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $data;
            $this->redirect(BASE_URL . 'simple.php?action=login');
        }
        
        if (!$this->userModel->verifyPassword($data['email'], $data['password'])) {
            $_SESSION['error'] = 'Invalid email or password.';
            $this->redirect(BASE_URL . 'simple.php?action=login');
        }
        
        $user = $this->userModel->getUserByEmail($data['email']);
        
        if ($user['status'] !== 'active') {
            $_SESSION['error'] = 'Your account is not active.';
            $this->redirect(BASE_URL . 'simple.php?action=login');
        }
        
        $this->userModel->updateLastLogin($user['id']);
        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['is_logged_in'] = true;
        
        $_SESSION['success'] = 'Welcome back, ' . $user['first_name'] . '!';
        $this->redirect(BASE_URL . 'simple.php');
    }
    
    public function logout() {
        session_destroy();
        $this->redirect(BASE_URL . 'simple.php?action=login');
    }
}

// Simple routing
$action = $_GET['action'] ?? 'home';

switch ($action) {
    case 'home':
        $controller = new HomeController();
        echo $controller->index();
        break;
        
    case 'login':
        $controller = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo $controller->attemptLogin();
        } else {
            echo $controller->login();
        }
        break;
        
    case 'logout':
        $controller = new AuthController();
        echo $controller->logout();
        break;
        
    default:
        $controller = new HomeController();
        echo $controller->index();
        break;
}
?>
