<?php

// Simple standalone version without CodeIgniter framework
session_start();

// Define basic constants
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
define('APPPATH', FCPATH . 'app' . DIRECTORY_SEPARATOR);
define('ROOTPATH', FCPATH);

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'fleur_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('BASE_URL', 'http://localhost/fleur/');

// Simple database connection
function getDB() {
    try {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $conn->set_charset("utf8mb4");
        return $conn;
    } catch (Exception $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}

// Simple routing
$request_uri = $_SERVER['REQUEST_URI'];
$request_uri = str_replace('/fleur', '', $request_uri);
$request_uri = rtrim($request_uri, '/');

// Parse the request
$parts = explode('/', trim($request_uri, '/'));
$controller = !empty($parts[0]) ? $parts[0] : 'home';
$method = !empty($parts[1]) ? $parts[1] : 'index';
$params = array_slice($parts, 2);

// Include required files
require_once APPPATH . 'Config/SimpleDatabase.php';
require_once APPPATH . 'Models/SimpleUserModel.php';
require_once APPPATH . 'Controllers/SimpleBaseController.php';
require_once APPPATH . 'Controllers/SimpleHome.php';
require_once APPPATH . 'Controllers/SimpleAuth.php';

// Simple controller loader
switch ($controller) {
    case 'home':
    case '':
        $controller = new SimpleHome();
        if ($method === 'index') {
            echo $controller->index();
        } else {
            http_response_code(404);
            echo "Page not found";
        }
        break;
        
    case 'login':
        $controller = new SimpleAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo $controller->attemptLogin();
        } else {
            echo $controller->login();
        }
        break;
        
    case 'logout':
        $controller = new SimpleAuth();
        echo $controller->logout();
        break;
        
    case 'register':
        $controller = new SimpleAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo $controller->attemptRegister();
        } else {
            echo $controller->register();
        }
        break;
        
    case 'admin':
        // Check if user is logged in and is admin
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
        
        require_once APPPATH . 'Controllers/Admin/Dashboard.php';
        require_once APPPATH . 'Controllers/Admin/Orders.php';
        
        if (empty($method) || $method === 'dashboard') {
            $controller = new App\Controllers\Admin\Dashboard();
            echo $controller->index();
        } elseif ($method === 'orders') {
            $controller = new App\Controllers\Admin\Orders();
            if (empty($params[0])) {
                echo $controller->index();
            } else {
                $action = $params[0];
                if ($action === 'view' && !empty($params[1])) {
                    echo $controller->view($params[1]);
                } else {
                    http_response_code(404);
                    echo "Admin page not found";
                }
            }
        } else {
            http_response_code(404);
            echo "Admin page not found";
        }
        break;
        
    default:
        http_response_code(404);
        echo "Page not found";
        break;
}
?>
