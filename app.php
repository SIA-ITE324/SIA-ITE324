<?php
// Simple app that doesn't rely on .htaccess or URL rewriting
session_start();

// Database connection
class SimpleDB {
    private static $instance = null;
    private $conn;
    
    private function __construct() {
        try {
            $this->conn = new mysqli('localhost', 'root', '', 'fleur_db');
            $this->conn->set_charset("utf8mb4");
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
        return $this->conn;
    }
    
    public function query($sql) {
        return $this->conn->query($sql);
    }
    
    public function escape($string) {
        return $this->conn->real_escape_string($string);
    }
    
    public function insertId() {
        return $this->conn->insert_id;
    }
    
    public function affectedRows() {
        return $this->conn->affected_rows;
    }
}

// Simple view function
function view($template, $data = []) {
    extract($data);
    $file = __DIR__ . '/app/Views/' . $template . '.php';
    if (file_exists($file)) {
        ob_start();
        include $file;
        return ob_get_clean();
    }
    return "View not found: " . $template;
}

// Simple redirect function
function redirect($url) {
    header("Location: $url");
    exit;
}

// Handle requests
$action = $_GET['action'] ?? 'home';

switch ($action) {
    case 'home':
        $db = SimpleDB::getInstance();
        $result = $db->query("SELECT * FROM products WHERE is_featured = 1 AND status = 'active' LIMIT 4");
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        
        echo view('simple_home', [
            'featured_products' => $products,
            'page_title' => 'Welcome to Fleur'
        ]);
        break;
        
    case 'register':
        if ($_POST) {
            $db = SimpleDB::getInstance();
            
            // Get form data
            $first_name = $db->escape($_POST['first_name']);
            $last_name = $db->escape($_POST['last_name']);
            $email = $db->escape($_POST['email']);
            $phone = $db->escape($_POST['phone']);
            $address = $db->escape($_POST['address']);
            $city = $db->escape($_POST['city']);
            $state = $db->escape($_POST['state']);
            $postal_code = $db->escape($_POST['postal_code']);
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            
            // Validate passwords match
            if ($password !== $confirm_password) {
                $_SESSION['error'] = 'Passwords do not match.';
                echo view('simple_register', ['page_title' => 'Register']);
                break;
            }
            
            // Validate password length
            if (strlen($password) < 6) {
                $_SESSION['error'] = 'Password must be at least 6 characters long.';
                echo view('simple_register', ['page_title' => 'Register']);
                break;
            }
            
            // Check if email already exists
            $result = $db->query("SELECT id FROM users WHERE email = '$email'");
            if ($result->num_rows > 0) {
                $_SESSION['error'] = 'Email address already exists.';
                echo view('simple_register', ['page_title' => 'Register']);
                break;
            }
            
            // Create new user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (first_name, last_name, email, phone, address, city, state, postal_code, password, role, status, created_at, updated_at) 
                    VALUES ('$first_name', '$last_name', '$email', '$phone', '$address', '$city', '$state', '$postal_code', '$hashed_password', 'customer', 'active', NOW(), NOW())";
            
            if ($db->query($sql)) {
                $_SESSION['success'] = 'Account created successfully! Please login.';
                redirect('app.php?action=login');
            } else {
                $_SESSION['error'] = 'Registration failed. Please try again.';
                echo view('simple_register', ['page_title' => 'Register']);
            }
        } else {
            echo view('simple_register', ['page_title' => 'Register']);
        }
        break;
        
    case 'login':
        if ($_POST) {
            $db = SimpleDB::getInstance();
            $email = $db->escape($_POST['email']);
            $password = $_POST['password'];
            $remember = isset($_POST['remember']) ? $_POST['remember'] : false;
            $ipAddress = $_SERVER['REMOTE_ADDR'];
            
            // Check login attempts
            $attempts = $db->query("SELECT COUNT(*) as count FROM login_attempts WHERE ip_address = '$ipAddress' AND created_at > DATE_SUB(NOW(), INTERVAL 15 MINUTE)")->fetch_assoc();
            
            if ($attempts['count'] >= 3) {
                $error = 'Too many login attempts. Please try again later.';
                echo view('simple_login', ['error' => $error]);
                break;
            }
            
            $result = $db->query("SELECT * FROM users WHERE email = '$email'");
            $user = $result->fetch_assoc();
            
            if ($user && password_verify($password, $user['password'])) {
                // Clear failed attempts on successful login
                $db->query("DELETE FROM login_attempts WHERE ip_address = '$ipAddress'");
                
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['is_logged_in'] = true;
                
                // Handle remember me
                if ($remember) {
                    setcookie('rememberedEmail', $email, time() + (86400 * 30), '/');
                    setcookie('rememberedName', $user['first_name'], time() + (86400 * 30), '/');
                }
                
                redirect('app.php?action=home');
            } else {
                // Record failed attempt
                $db->query("INSERT INTO login_attempts (ip_address, created_at) VALUES ('$ipAddress', NOW())");
                
                $error = 'Invalid email or password';
                echo view('simple_login', ['error' => $error]);
            }
        } else {
            echo view('simple_login', []);
        }
        break;
        
    case 'track-order':
        $orderId = $_GET['order_id'] ?? '';
        $email = $_GET['email'] ?? '';
        
        if (!$orderId) {
            redirect('app.php?action=login');
        }
        
        $db = SimpleDB::getInstance();
        $orderId = $db->escape($orderId);
        
        // Try to find order by order number (with or without email)
        $query = "SELECT * FROM orders WHERE order_number = '$orderId'";
        if ($email) {
            $email = $db->escape($email);
            $query .= " AND (customer_email = '$email' OR customer_email IS NULL OR customer_email = '')";
        }
        
        $result = $db->query($query);
        $order = $result->fetch_assoc();
        
        if (!$order) {
            $_SESSION['error'] = 'Order not found.';
            redirect('app.php?action=login');
        }
        
        // Get detailed order items with product information
        $itemsResult = $db->query("SELECT oi.*, p.description, p.category FROM order_items oi LEFT JOIN products p ON oi.product_id = p.id WHERE oi.order_id = " . $order['id']);
        $items = [];
        while ($row = $itemsResult->fetch_assoc()) {
            $items[] = $row;
        }
        
        // Get customer details if available
        $customerResult = $db->query("SELECT first_name, last_name, phone FROM users WHERE email = '" . $order['customer_email'] . "'");
        $customer = $customerResult->fetch_assoc();
        
        if ($customer) {
            $order['customer_name'] = $customer['first_name'] . ' ' . $customer['last_name'];
            $order['customer_phone'] = $customer['phone'];
        }
        
        echo view('guest_order_tracking', [
            'order' => $order,
            'items' => $items
        ]);
        break;
        
            
    case 'logout':
        session_destroy();
        redirect('app.php?action=login');
        break;
        
    case 'add_product':
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['user_role'] !== 'admin') {
            redirect('app.php?action=login');
        }
        
        echo view('simple_product_edit', [
            'page_title' => 'Add New Product',
            'product' => null
        ]);
        break;
        
    case 'add_order':
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['user_role'] !== 'admin') {
            redirect('app.php?action=login');
        }
        
        // Create manual order form for walk-in customers
        $db = SimpleDB::getInstance();
        $result = $db->query("SELECT * FROM products WHERE status = 'active' ORDER BY name");
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        
        echo view('simple_manual_order', [
            'page_title' => 'Create Manual Order',
            'products' => $products
        ]);
        break;
        
    case 'export_sales':
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['user_role'] !== 'admin') {
            redirect('app.php?action=login');
        }
        
        // Export today's sales
        $db = SimpleDB::getInstance();
        $today = date('Y-m-d');
        $result = $db->query("SELECT o.*, CONCAT(u.first_name, ' ', u.last_name) as customer_name, u.email as customer_email FROM orders o LEFT JOIN users u ON o.customer_id = u.id WHERE DATE(o.created_at) = '$today' ORDER BY o.created_at DESC");
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="sales_export_' . $today . '.csv"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Order #', 'Customer', 'Email', 'Amount', 'Status', 'Date']);
        
        while ($row = $result->fetch_assoc()) {
            fputcsv($output, [
                $row['order_number'],
                $row['customer_name'] ?? 'Guest',
                $row['customer_email'] ?? '',
                $row['total_amount'],
                $row['status'],
                $row['created_at']
            ]);
        }
        
        fclose($output);
        exit;
        break;
        
    case 'print_order':
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['user_role'] !== 'admin') {
            redirect('app.php?action=login');
        }
        
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $order_id = (int)$_GET['id'];
            $db = SimpleDB::getInstance();
            
            // Get order details
            $result = $db->query("SELECT o.*, CONCAT(u.first_name, ' ', u.last_name) as customer_name, u.email as customer_email FROM orders o LEFT JOIN users u ON o.customer_id = u.id WHERE o.id = $order_id");
            $order = $result->fetch_assoc();
            
            // Get order items
            $result = $db->query("SELECT oi.*, p.name as product_name FROM order_items oi LEFT JOIN products p ON oi.product_id = p.id WHERE oi.order_id = $order_id");
            $order_items = [];
            while ($row = $result->fetch_assoc()) {
                $order_items[] = $row;
            }
            
            echo view('simple_order_print', [
                'order' => $order,
                'order_items' => $order_items
            ]);
        }
        break;
        
    case 'edit_custom_bouquet':
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['user_role'] !== 'admin') {
            redirect('app.php?action=login');
        }
        
        if (isset($_GET['order_id']) && isset($_GET['order_item_id']) && is_numeric($_GET['order_id']) && is_numeric($_GET['order_item_id'])) {
            $order_id = (int)$_GET['order_id'];
            $order_item_id = (int)$_GET['order_item_id'];
            $db = SimpleDB::getInstance();
            
            // Get order item details
            $result = $db->query("SELECT oi.*, o.order_number, CONCAT(u.first_name, ' ', u.last_name) as customer_name FROM order_items oi LEFT JOIN orders o ON oi.order_id = o.id LEFT JOIN users u ON o.customer_id = u.id WHERE oi.id = $order_item_id AND oi.order_id = $order_id");
            $order_item = $result->fetch_assoc();
            
            // Debug: Check if order item exists
            if (!$order_item) {
                $_SESSION['error'] = 'Order item not found';
                redirect('app.php?action=edit_order&id=' . $order_id);
            }
            
            // Debug: Check custom bouquet details
            if (empty($order_item['custom_bouquet_details'])) {
                $_SESSION['error'] = 'This is not a custom bouquet item';
                redirect('app.php?action=edit_order&id=' . $order_id);
            }
            
            $custom_details = json_decode($order_item['custom_bouquet_details'], true);
            
            // Debug: Check if JSON decode worked
            if (!$custom_details) {
                $_SESSION['error'] = 'Error reading custom bouquet details';
                redirect('app.php?action=edit_order&id=' . $order_id);
            }
            
            // Get available flowers for editing
            $result = $db->query("SELECT * FROM products WHERE status = 'active' ORDER BY name");
            $flowers = [];
            while ($row = $result->fetch_assoc()) {
                $flowers[] = $row;
            }
            
            echo view('simple_edit_custom_bouquet', [
                'order' => $order_item, // Fix variable name
                'order_item' => $order_item,
                'custom_details' => $custom_details,
                'flowers' => $flowers,
                'page_title' => 'Edit Custom Bouquet'
            ]);
        } else {
            $_SESSION['error'] = 'Invalid parameters';
            redirect('app.php?action=orders');
        }
        break;
        
    case 'update_custom_bouquet':
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['user_role'] !== 'admin') {
            redirect('app.php?action=login');
        }
        
        if ($_POST && isset($_POST['order_item_id']) && isset($_POST['order_id'])) {
            $order_item_id = (int)$_POST['order_item_id'];
            $order_id = (int)$_POST['order_id'];
            $db = SimpleDB::getInstance();
            
            try {
                // Get current order item details
                $result = $db->query("SELECT * FROM order_items WHERE id = $order_item_id AND order_id = $order_id");
                $order_item = $result->fetch_assoc();
                
                if (!$order_item) {
                    $_SESSION['error'] = 'Order item not found';
                    redirect('app.php?action=edit_order&id=' . $order_id);
                }
                
                // Build custom bouquet details
                $custom_details = [
                    'size' => $_POST['size'] ?? 'medium',
                    'style' => $_POST['style'] ?? 'classic',
                    'color_theme' => $_POST['color_theme'] ?? 'romantic',
                    'message' => $_POST['message'] ?? '',
                    'flowers' => []
                ];
                
                // Process flower selections
                $flower_cost = 0;
                if (isset($_POST['flowers'])) {
                    foreach ($_POST['flowers'] as $flower_id => $flower_data) {
                        if (isset($flower_data['selected']) && $flower_data['selected'] == '1') {
                            $quantity = (int)($flower_data['quantity'] ?? 1);
                            if ($quantity > 0) {
                                // Get flower details
                                $result = $db->query("SELECT name, price FROM products WHERE id = " . (int)$flower_id);
                                $flower = $result->fetch_assoc();
                                
                                if ($flower) {
                                    $custom_details['flowers'][] = [
                                        'id' => (int)$flower_id,
                                        'name' => $flower['name'],
                                        'quantity' => $quantity,
                                        'price' => (float)$flower['price']
                                    ];
                                    $flower_cost += $flower['price'] * $quantity;
                                }
                            }
                        }
                    }
                }
                
                // Calculate new base price based on size
                $size_prices = [
                    'small' => 500,
                    'medium' => 750,
                    'large' => 1000,
                    'xlarge' => 1500
                ];
                $base_price = $size_prices[$custom_details['size']] ?? 750;
                
                // Apply price adjustment if provided
                $price_adjustment = (float)($_POST['price_adjustment'] ?? 0);
                $new_unit_price = $base_price + $flower_cost + $price_adjustment;
                $new_total_price = $new_unit_price * $order_item['quantity'];
                
                // Update order item
                $custom_details_json = json_encode($custom_details);
                $db->query("UPDATE order_items SET 
                    custom_bouquet_details = '" . $db->escape($custom_details_json) . "',
                    unit_price = $new_unit_price,
                    total_price = $new_total_price
                    WHERE id = $order_item_id");
                
                // Update order total
                $result = $db->query("SELECT SUM(total_price) as new_total FROM order_items WHERE order_id = $order_id");
                $new_order_total = $result->fetch_assoc()['new_total'];
                $db->query("UPDATE orders SET total_amount = $new_order_total WHERE id = $order_id");
                
                $_SESSION['success'] = 'Custom bouquet updated successfully';
                redirect('app.php?action=edit_order&id=' . $order_id);
                
            } catch (Exception $e) {
                $_SESSION['error'] = 'Error updating custom bouquet: ' . $e->getMessage();
                redirect('app.php?action=edit_custom_bouquet&order_id=' . $order_id . '&order_item_id=' . $order_item_id);
            }
        }
        break;
        
    case 'admin_bouquet_builder':
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['user_role'] !== 'admin') {
            redirect('app.php?action=login');
        }
        
        $db = SimpleDB::getInstance();
        
        // Get flowers for selection
        $result = $db->query("SELECT * FROM products WHERE status = 'active' ORDER BY name");
        $flowers = [];
        while ($row = $result->fetch_assoc()) {
            $flowers[] = $row;
        }
        
        // Define bouquet sizes, styles, and themes
        $bouquet_sizes = [
            'small' => ['name' => 'Small', 'base_price' => 500, 'max_flowers' => 6],
            'medium' => ['name' => 'Medium', 'base_price' => 750, 'max_flowers' => 12],
            'large' => ['name' => 'Large', 'base_price' => 1000, 'max_flowers' => 18],
            'xlarge' => ['name' => 'Extra Large', 'base_price' => 1500, 'max_flowers' => 24]
        ];
        
        $bouquet_styles = [
            'modern' => ['name' => 'Modern Linear', 'description' => 'Clean, contemporary arrangement'],
            'classic' => ['name' => 'Classic Round', 'description' => 'Traditional circular bouquet'],
            'cascading' => ['name' => 'Cascading', 'description' => 'Elegant flowing arrangement'],
            'compact' => ['name' => 'Compact', 'description' => 'Dense, tight arrangement']
        ];
        
        $color_themes = [
            'romantic' => ['name' => 'Romantic', 'colors' => ['#ff6b9d', '#feca57', '#ff9ff3']],
            'tropical' => ['name' => 'Tropical', 'colors' => ['#f9ca24', '#f0932b', '#ff6348']],
            'elegant' => ['name' => 'Elegant', 'colors' => ['#636e72', '#2d3436', '#b2bec3']],
            'vibrant' => ['name' => 'Vibrant', 'colors' => ['#ff9ff3', '#feca57', '#48dbfb', '#ff6b9d']],
            'pastel' => ['name' => 'Pastel', 'colors' => ['#ffeaa7', '#fab1a0', '#a29bfe', '#fd79a8']],
            'monochrome' => ['name' => 'Monochrome', 'colors' => ['#dfe6e9', '#636e72', '#2d3436']]
        ];
        
        echo view('simple_admin_bouquet_builder', [
            'flowers' => $flowers,
            'bouquet_sizes' => $bouquet_sizes,
            'bouquet_styles' => $bouquet_styles,
            'color_themes' => $color_themes,
            'page_title' => 'Admin Custom Bouquet Builder'
        ]);
        break;
        
    case 'save_admin_bouquet':
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['user_role'] !== 'admin') {
            redirect('app.php?action=login');
        }
        
        if ($_POST) {
            try {
                $db = SimpleDB::getInstance();
                
                // Get bouquet data
                $bouquet_name = $db->escape($_POST['bouquet_name'] ?? 'Admin Custom Bouquet');
                $size = $db->escape($_POST['size']);
                $style = $db->escape($_POST['style']);
                $color_theme = $db->escape($_POST['color_theme']);
                $message = $db->escape($_POST['message'] ?? '');
                $customer_name = $db->escape($_POST['customer_name'] ?? '');
                $customer_email = $db->escape($_POST['customer_email'] ?? '');
                $delivery_date = $db->escape($_POST['delivery_date'] ?? '');
                $delivery_time = $db->escape($_POST['delivery_time'] ?? '');
                $total_price = (float)$_POST['total_price'];
                
                // Calculate add-on costs
                $addOnCost = 0;
                if (isset($_POST['add_ons'])) {
                    foreach ($_POST['add_ons'] as $addOn => $value) {
                        if ($value == '1') {
                            $addOnCost += match($addOn) {
                                'vase' => 250,
                                'chocolates' => 150,
                                'teddy_bear' => 200,
                                default => 0
                            };
                        }
                    }
                }
                
                // Create a custom bouquet product
                $sql = "INSERT INTO products (name, description, price, sku, category_id, stock_quantity, min_stock_level, status, is_featured, created_at, updated_at) 
                        VALUES ('$bouquet_name', 'Admin custom bouquet: $size $style arrangement', $total_price, 'ADMIN-" . time() . "', 1, 999, 1, 'active', 0, NOW(), NOW())";
                
                if ($db->query($sql)) {
                    $product_id = $db->insertId();
                    
                    // Store bouquet customization details
                    $custom_details = [
                        'size' => $size,
                        'style' => $style,
                        'color_theme' => $color_theme,
                        'message' => $message,
                        'flowers' => $_POST['flowers'] ?? [],
                        'add_ons' => $_POST['add_ons'] ?? [],
                        'customer_name' => $customer_name,
                        'customer_email' => $customer_email,
                        'delivery_date' => $delivery_date,
                        'delivery_time' => $delivery_time,
                        'created_by_admin' => true,
                        'admin_name' => $_SESSION['user_name']
                    ];
                    
                    // Add to cart if customer info provided
                    if (!empty($customer_name) || !empty($customer_email)) {
                        if (!isset($_SESSION['cart'])) {
                            $_SESSION['cart'] = [];
                        }
                        if (!isset($_SESSION['custom_bouquets'])) {
                            $_SESSION['custom_bouquets'] = [];
                        }
                        
                        $_SESSION['cart'][$product_id] = 1;
                        $_SESSION['custom_bouquets'][$product_id] = [
                            'product_id' => $product_id,
                            'name' => $bouquet_name,
                            'size' => $size,
                            'style' => $style,
                            'color_theme' => $color_theme,
                            'message' => $message,
                            'flowers' => $_POST['flowers'] ?? [],
                            'add_ons' => $_POST['add_ons'] ?? [],
                            'total_price' => $total_price,
                            'customer_name' => $customer_name,
                            'customer_email' => $customer_email,
                            'delivery_date' => $delivery_date,
                            'delivery_time' => $delivery_time
                        ];
                        
                        $_SESSION['success'] = 'Admin custom bouquet created and added to cart for customer!';
                        redirect('app.php?action=cart');
                    } else {
                        $_SESSION['success'] = 'Admin custom bouquet created successfully! Product ID: ' . $product_id;
                        redirect('app.php?action=admin_bouquet_builder');
                    }
                } else {
                    $_SESSION['error'] = 'Error creating admin custom bouquet. Please try again.';
                    redirect('app.php?action=admin_bouquet_builder');
                }
            } catch (Exception $e) {
                $_SESSION['error'] = 'Error saving admin custom bouquet. Please try again.';
                redirect('app.php?action=admin_bouquet_builder');
            }
        }
        break;
        
    case 'manage_bouquet_builder':
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['user_role'] !== 'admin') {
            redirect('app.php?action=login');
        }
        
        $db = SimpleDB::getInstance();
        
        // Get all flowers for bouquet builder
        $result = $db->query("SELECT * FROM products WHERE status = 'active' ORDER BY name");
        $flowers = [];
        while ($row = $result->fetch_assoc()) {
            $flowers[] = $row;
        }
        
        // Get bouquet builder settings from database or use defaults
        $bouquet_sizes = [
            'small' => ['name' => 'Small', 'base_price' => 500, 'max_flowers' => 6, 'icon' => 'fa-seedling'],
            'medium' => ['name' => 'Medium', 'base_price' => 750, 'max_flowers' => 12, 'icon' => 'fa-leaf'],
            'large' => ['name' => 'Large', 'base_price' => 1000, 'max_flowers' => 18, 'icon' => 'fa-tree'],
            'xlarge' => ['name' => 'Extra Large', 'base_price' => 1500, 'max_flowers' => 24, 'icon' => 'fa-spa']
        ];
        
        $bouquet_styles = [
            'modern' => ['name' => 'Modern Linear', 'description' => 'Clean, contemporary arrangement', 'icon' => 'fa-grip-lines'],
            'classic' => ['name' => 'Classic Round', 'description' => 'Traditional circular bouquet', 'icon' => 'fa-circle'],
            'cascading' => ['name' => 'Cascading', 'description' => 'Elegant flowing arrangement', 'icon' => 'fa-water'],
            'compact' => ['name' => 'Compact', 'description' => 'Dense, tight arrangement', 'icon' => 'fa-compress']
        ];
        
        $color_themes = [
            'romantic' => ['name' => 'Romantic', 'colors' => ['#ff6b9d', '#feca57', '#ff9ff3'], 'description' => 'Soft pinks and yellows'],
            'tropical' => ['name' => 'Tropical', 'colors' => ['#f9ca24', '#f0932b', '#ff6348'], 'description' => 'Bright oranges and yellows'],
            'elegant' => ['name' => 'Elegant', 'colors' => ['#636e72', '#2d3436', '#b2bec3'], 'description' => 'Sophisticated grays'],
            'vibrant' => ['name' => 'Vibrant', 'colors' => ['#ff9ff3', '#feca57', '#48dbfb', '#ff6b9d'], 'description' => 'Bold and colorful'],
            'pastel' => ['name' => 'Pastel', 'colors' => ['#ffeaa7', '#fab1a0', '#a29bfe', '#fd79a8'], 'description' => 'Soft and gentle'],
            'monochrome' => ['name' => 'Monochrome', 'colors' => ['#dfe6e9', '#636e72', '#2d3436'], 'description' => 'Classic black and white']
        ];
        
        $add_ons = [
            'vase' => ['name' => 'Add a Vase', 'price' => 250, 'description' => 'Beautiful glass vase perfect for your bouquet', 'icon' => 'fa-wine-glass'],
            'chocolates' => ['name' => 'Add Chocolates', 'price' => 150, 'description' => 'Premium chocolate assortment', 'icon' => 'fa-candy-cane'],
            'teddy_bear' => ['name' => 'Add a Teddy Bear', 'price' => 200, 'description' => 'Cuddly teddy bear companion', 'icon' => 'fa-bear']
        ];
        
        echo view('simple_manage_bouquet_builder', [
            'flowers' => $flowers,
            'bouquet_sizes' => $bouquet_sizes,
            'bouquet_styles' => $bouquet_styles,
            'color_themes' => $color_themes,
            'add_ons' => $add_ons,
            'page_title' => 'Bouquet Builder Settings'
        ]);
        break;
        
    case 'admin':
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['user_role'] !== 'admin') {
            redirect('app.php?action=login');
        }
        
        $db = SimpleDB::getInstance();
        
        // Get dashboard stats
        $stats = [
            'total_orders' => $db->query("SELECT COUNT(*) as count FROM orders")->fetch_assoc()['count'],
            'total_products' => $db->query("SELECT COUNT(*) as count FROM products")->fetch_assoc()['count'],
            'total_users' => $db->query("SELECT COUNT(*) as count FROM users WHERE role = 'customer'")->fetch_assoc()['count'],
            'total_revenue' => $db->query("SELECT SUM(total_amount) as total FROM orders")->fetch_assoc()['total'] ?? 0
        ];
        
        // Get sales trend data (last 7 days)
        $sales_trend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $result = $db->query("SELECT COUNT(*) as orders, COALESCE(SUM(total_amount), 0) as revenue FROM orders WHERE DATE(created_at) = '$date'");
            $data = $result->fetch_assoc();
            $sales_trend[] = [
                'date' => date('M d', strtotime($date)),
                'orders' => (int)$data['orders'],
                'revenue' => (float)$data['revenue']
            ];
        }
        
        // Get order status distribution
        $status_result = $db->query("SELECT status, COUNT(*) as count FROM orders GROUP BY status");
        $order_status_distribution = [];
        while ($row = $status_result->fetch_assoc()) {
            $order_status_distribution[] = [
                'status' => ucfirst($row['status']),
                'count' => (int)$row['count']
            ];
        }
        
        // Get low stock alerts
        $low_stock_result = $db->query("SELECT name, stock_quantity, min_stock_level FROM products WHERE stock_quantity <= min_stock_level ORDER BY stock_quantity ASC LIMIT 5");
        $low_stock_alerts = [];
        while ($row = $low_stock_result->fetch_assoc()) {
            $low_stock_alerts[] = $row;
        }
        
        // Get recent orders with customer names
        $result = $db->query("SELECT o.*, CONCAT(u.first_name, ' ', u.last_name) as customer_name, u.email as customer_email FROM orders o LEFT JOIN users u ON o.customer_id = u.id ORDER BY o.created_at DESC LIMIT 5");
        $recent_orders = [];
        while ($row = $result->fetch_assoc()) {
            $recent_orders[] = $row;
        }
        
        // Get top selling products
        $top_products_result = $db->query("SELECT p.name, COUNT(oi.id) as order_count, SUM(oi.quantity) as total_quantity FROM products p LEFT JOIN order_items oi ON p.id = oi.product_id LEFT JOIN orders o ON oi.order_id = o.id WHERE o.status != 'cancelled' GROUP BY p.id ORDER BY order_count DESC LIMIT 3");
        $top_products = [];
        while ($row = $top_products_result->fetch_assoc()) {
            if ($row['order_count'] > 0) {
                $top_products[] = $row;
            }
        }
        
        echo view('simple_admin_dashboard', [
            'stats' => $stats,
            'recent_orders' => $recent_orders,
            'sales_trend' => $sales_trend,
            'order_status_distribution' => $order_status_distribution,
            'low_stock_alerts' => $low_stock_alerts,
            'top_products' => $top_products,
            'page_title' => 'Admin Dashboard'
        ]);
        break;
        
    case 'profile':
        if (!isset($_SESSION['is_logged_in'])) {
            redirect('app.php?action=login');
        }
        
        $db = SimpleDB::getInstance();
        $user_id = $_SESSION['user_id'];
        $result = $db->query("SELECT * FROM users WHERE id = $user_id");
        $user = $result->fetch_assoc();
        
        echo view('simple_profile', [
            'user' => $user,
            'page_title' => 'My Profile'
        ]);
        break;
        
    case 'orders':
        if (!isset($_SESSION['is_logged_in'])) {
            redirect('app.php?action=login');
        }
        
        $db = SimpleDB::getInstance();
        $user_id = $_SESSION['user_id'];
        
        // Build query with filters
        $where_conditions = [];
        $params = [];
        
        if ($_SESSION['user_role'] !== 'admin') {
            $where_conditions[] = "customer_id = $user_id";
        }
        
        // Status filter
        if (!empty($_GET['status'])) {
            $status = $db->escape($_GET['status']);
            $where_conditions[] = "status = '$status'";
        }
        
        // Payment status filter
        if (!empty($_GET['payment_status'])) {
            $payment_status = $db->escape($_GET['payment_status']);
            $where_conditions[] = "payment_status = '$payment_status'";
        }
        
        // Date range filter
        if (!empty($_GET['date_from'])) {
            $date_from = $db->escape($_GET['date_from']);
            $where_conditions[] = "created_at >= '$date_from 00:00:00'";
        }
        
        if (!empty($_GET['date_to'])) {
            $date_to = $db->escape($_GET['date_to']);
            $where_conditions[] = "created_at <= '$date_to 23:59:59'";
        }
        
        // Search filter
        if (!empty($_GET['search'])) {
            $search = $db->escape($_GET['search']);
            $where_conditions[] = "(order_number LIKE '%$search%' OR shipping_address LIKE '%$search%')";
        }
        
        $where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';
        
        $result = $db->query("SELECT * FROM orders $where_clause ORDER BY created_at DESC");
        
        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        
        echo view('simple_orders', [
            'orders' => $orders,
            'page_title' => $_SESSION['user_role'] === 'admin' ? 'All Orders' : 'My Orders'
        ]);
        break;
        
    case 'bulk_update_orders':
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['user_role'] !== 'admin') {
            redirect('app.php?action=login');
        }
        
        if ($_POST && isset($_POST['order_ids'])) {
            $db = SimpleDB::getInstance();
            $order_ids = array_map('intval', $_POST['order_ids']);
            
            $update_fields = [];
            
            if (!empty($_POST['status'])) {
                $status = $db->escape($_POST['status']);
                $update_fields[] = "status = '$status'";
            }
            
            if (!empty($_POST['payment_status'])) {
                $payment_status = $db->escape($_POST['payment_status']);
                $update_fields[] = "payment_status = '$payment_status'";
            }
            
            if (!empty($_POST['driver'])) {
                $driver = $db->escape($_POST['driver']);
                $update_fields[] = "assigned_driver = '$driver'";
            }
            
            if (!empty($_POST['notes'])) {
                $notes = $db->escape($_POST['notes']);
                $update_fields[] = "admin_notes = CONCAT(COALESCE(admin_notes, ''), '\n\n', NOW(), ': $notes')";
            }
            
            if (!empty($update_fields)) {
                $update_clause = implode(', ', $update_fields);
                $order_ids_str = implode(',', $order_ids);
                
                $db->query("UPDATE orders SET $update_clause WHERE id IN ($order_ids_str)");
            }
        }
        
        redirect('app.php?action=orders');
        break;
        
    case 'assign_driver':
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['user_role'] !== 'admin') {
            redirect('app.php?action=login');
        }
        
        if ($_POST && isset($_POST['order_id']) && isset($_POST['driver_id'])) {
            $db = SimpleDB::getInstance();
            $order_id = (int)$_POST['order_id'];
            $driver_id = $db->escape($_POST['driver_id']);
            
            $db->query("UPDATE orders SET assigned_driver = '$driver_id' WHERE id = $order_id");
        }
        
        redirect('app.php?action=orders');
        break;
        
    case 'print_job_sheet':
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['user_role'] !== 'admin') {
            redirect('app.php?action=login');
        }
        
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            redirect('app.php?action=orders');
        }
        
        $order_id = (int)$_GET['id'];
        $db = SimpleDB::getInstance();
        
        // Get order details
        $result = $db->query("SELECT * FROM orders WHERE id = $order_id");
        $order = $result->fetch_assoc();
        
        if (!$order) {
            redirect('app.php?action=orders');
        }
        
        // Get order items
        $items_result = $db->query("SELECT * FROM order_items WHERE order_id = $order_id");
        $items = [];
        while ($row = $items_result->fetch_assoc()) {
            $items[] = $row;
        }
        $order['items'] = $items;
        
        echo view('simple_order_print', [
            'order' => $order,
            'page_title' => 'Job Sheet - ' . $order['order_number']
        ]);
        break;
        
    case 'check_new_orders':
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['user_role'] !== 'admin') {
            echo json_encode(['hasNewOrders' => false]);
            exit;
        }
        
        $db = SimpleDB::getInstance();
        $last_check = $_GET['last_check'] ?? date('Y-m-d H:i:s', strtotime('-5 minutes'));
        
        $result = $db->query("SELECT COUNT(*) as new_count FROM orders WHERE created_at > '$last_check'");
        $row = $result->fetch_assoc();
        
        echo json_encode([
            'hasNewOrders' => $row['new_count'] > 0,
            'newOrderCount' => (int)$row['new_count'],
            'last_check' => date('Y-m-d H:i:s')
        ]);
        exit;
        
    case 'order_details':
        if (!isset($_SESSION['is_logged_in'])) {
            redirect('app.php?action=login');
        }
        
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            redirect('app.php?action=orders');
        }
        
        $order_id = (int)$_GET['id'];
        $db = SimpleDB::getInstance();
        
        // Get order details
        $result = $db->query("SELECT * FROM orders WHERE id = $order_id");
        $order = $result->fetch_assoc();
        
        if (!$order) {
            redirect('app.php?action=orders');
        }
        
        // Check if user has permission to view this order
        if ($_SESSION['user_role'] !== 'admin' && $order['customer_id'] != $_SESSION['user_id']) {
            redirect('app.php?action=orders');
        }
        
        // Get order items
        $result = $db->query("SELECT oi.*, p.name as product_name, p.image as product_image FROM order_items oi LEFT JOIN products p ON oi.product_id = p.id WHERE oi.order_id = $order_id");
        $order_items = [];
        while ($row = $result->fetch_assoc()) {
            $order_items[] = $row;
        }
        
        echo view('simple_order_details', [
            'order' => $order,
            'order_items' => $order_items,
            'page_title' => 'Order Details - ' . $order['order_number']
        ]);
        break;
        
    case 'edit_order':
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['user_role'] !== 'admin') {
            redirect('app.php?action=login');
        }
        
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $order_id = (int)$_GET['id'];
            $db = SimpleDB::getInstance();
            
            // Get order details
            $result = $db->query("SELECT * FROM orders WHERE id = $order_id");
            $order = $result->fetch_assoc();
            
            // Get order items
            $result = $db->query("SELECT oi.*, p.name as product_name FROM order_items oi LEFT JOIN products p ON oi.product_id = p.id WHERE oi.order_id = $order_id");
            $order_items = [];
            while ($row = $result->fetch_assoc()) {
                $order_items[] = $row;
            }
            
            echo view('simple_order_edit', [
                'order' => $order,
                'order_items' => $order_items,
                'page_title' => 'Edit Order'
            ]);
        }
        break;
        
    case 'update_order':
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['user_role'] !== 'admin') {
            redirect('app.php?action=login');
        }
        
        if ($_POST && isset($_POST['order_id']) && is_numeric($_POST['order_id'])) {
            $order_id = (int)$_POST['order_id'];
            $db = SimpleDB::getInstance();
            
            $status = $db->escape($_POST['status']);
            $payment_status = $db->escape($_POST['payment_status']);
            $tracking_number = $db->escape($_POST['tracking_number'] ?? '');
            $admin_notes = $db->escape($_POST['admin_notes'] ?? '');
            
            $sql = "UPDATE orders SET status = '$status', payment_status = '$payment_status', tracking_number = '$tracking_number', admin_notes = '$admin_notes', updated_at = NOW() WHERE id = $order_id";
            
            if ($db->query($sql)) {
                $_SESSION['success'] = 'Order updated successfully!';
                redirect('app.php?action=orders');
            } else {
                $_SESSION['error'] = 'Error updating order. Please try again.';
                redirect("app.php?action=edit_order&id=$order_id");
            }
        }
        break;
        
    case 'export_orders':
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['user_role'] !== 'admin') {
            redirect('app.php?action=login');
        }
        
        $db = SimpleDB::getInstance();
        $format = $_GET['format'] ?? 'csv';
        
        // Get all orders with customer info
        $result = $db->query("SELECT o.*, u.first_name, u.last_name, u.email FROM orders o LEFT JOIN users u ON o.customer_id = u.id ORDER BY o.created_at DESC");
        
        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        
        if ($format === 'csv') {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="orders_' . date('Y-m-d') . '.csv"');
            
            $output = fopen('php://output', 'w');
            
            // CSV header
            fputcsv($output, ['Order ID', 'Order Number', 'Customer Name', 'Customer Email', 'Status', 'Payment Status', 'Total Amount', 'Created At']);
            
            // CSV data
            foreach ($orders as $order) {
                fputcsv($output, [
                    $order['id'],
                    $order['order_number'],
                    $order['first_name'] . ' ' . $order['last_name'],
                    $order['email'],
                    $order['status'],
                    $order['payment_status'],
                    $order['total_amount'],
                    $order['created_at']
                ]);
            }
            
            fclose($output);
            exit;
        } elseif ($format === 'excel') {
            // Simple Excel format (tab-separated)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="orders_' . date('Y-m-d') . '.xls"');
            
            $output = fopen('php://output', 'w');
            
            // Excel header
            fwrite($output, "Order ID\tOrder Number\tCustomer Name\tCustomer Email\tStatus\tPayment Status\tTotal Amount\tCreated At\n");
            
            // Excel data
            foreach ($orders as $order) {
                fwrite($output, "{$order['id']}\t{$order['order_number']}\t{$order['first_name']} {$order['last_name']}\t{$order['email']}\t{$order['status']}\t{$order['payment_status']}\t{$order['total_amount']}\t{$order['created_at']}\n");
            }
            
            fclose($output);
            exit;
        }
        break;
        
    case 'delete_order':
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['user_role'] !== 'admin') {
            redirect('app.php?action=login');
        }
        
        if ($_POST && isset($_POST['order_id']) && is_numeric($_POST['order_id'])) {
            $order_id = (int)$_POST['order_id'];
            $db = SimpleDB::getInstance();
            
            try {
                // Delete order items first
                $db->query("DELETE FROM order_items WHERE order_id = $order_id");
                
                // Delete the order
                $db->query("DELETE FROM orders WHERE id = $order_id");
                
                $_SESSION['success'] = 'Order deleted successfully!';
                redirect('app.php?action=orders');
            } catch (Exception $e) {
                $_SESSION['error'] = 'Error deleting order. Please try again.';
                redirect('app.php?action=orders');
            }
        }
        break;
        
    case 'bouquet_builder':
        // Custom bouquet builder - accessible to all users
        $db = SimpleDB::getInstance();
        
        try {
            // Get all available flowers for customization
            $result = $db->query("SELECT * FROM products WHERE status = 'active' ORDER BY name");
            $flowers = [];
            while ($row = $result->fetch_assoc()) {
                $flowers[] = $row;
            }
            
            // Get bouquet sizes and pricing
            $bouquet_sizes = [
                'small' => ['name' => 'Small', 'base_price' => 500, 'max_flowers' => 6],
                'medium' => ['name' => 'Medium', 'base_price' => 800, 'max_flowers' => 12],
                'large' => ['name' => 'Large', 'base_price' => 1200, 'max_flowers' => 20],
                'xlarge' => ['name' => 'Extra Large', 'base_price' => 1800, 'max_flowers' => 30]
            ];
            
            // Get bouquet styles
            $bouquet_styles = [
                'classic' => ['name' => 'Classic Round', 'description' => 'Traditional round arrangement'],
                'modern' => ['name' => 'Modern Linear', 'description' => 'Contemporary linear design'],
                'garden' => ['name' => 'Garden Style', 'description' => 'Natural, loose arrangement'],
                'minimalist' => ['name' => 'Minimalist', 'description' => 'Simple and elegant']
            ];
            
            // Get color themes
            $color_themes = [
                'romantic' => ['name' => 'Romantic', 'colors' => ['Red', 'Pink', 'White']],
                'vibrant' => ['name' => 'Vibrant', 'colors' => ['Orange', 'Yellow', 'Purple']],
                'elegant' => ['name' => 'Elegant', 'colors' => ['White', 'Cream', 'Pale Pink']],
                'tropical' => ['name' => 'Tropical', 'colors' => ['Red', 'Orange', 'Yellow']]
            ];
            
            echo view('simple_bouquet_builder', [
                'flowers' => $flowers,
                'bouquet_sizes' => $bouquet_sizes,
                'bouquet_styles' => $bouquet_styles,
                'color_themes' => $color_themes,
                'page_title' => 'Create Your Custom Bouquet'
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error loading bouquet builder. Please try again.';
            redirect('app.php?action=products');
        }
        break;
        
    case 'save_bouquet':
        if ($_POST) {
            try {
                $db = SimpleDB::getInstance();
                
                // Get bouquet data
                $bouquet_name = $db->escape($_POST['bouquet_name'] ?? 'Custom Bouquet');
                $size = $db->escape($_POST['size']);
                $style = $db->escape($_POST['style']);
                $color_theme = $db->escape($_POST['color_theme']);
                $message = $db->escape($_POST['message'] ?? '');
                $total_price = (float)$_POST['total_price'];
                
                // Create a unique custom bouquet ID (not a database product)
                $custom_bouquet_id = 'CUSTOM-' . time() . '-' . rand(1000, 9999);
                
                // Add to cart as custom item
                if (!isset($_SESSION['cart'])) {
                    $_SESSION['cart'] = [];
                }
                if (!isset($_SESSION['custom_bouquets'])) {
                    $_SESSION['custom_bouquets'] = [];
                }
                
                $_SESSION['cart'][$custom_bouquet_id] = 1;
                $_SESSION['custom_bouquets'][$custom_bouquet_id] = [
                    'custom_id' => $custom_bouquet_id,
                    'name' => $bouquet_name,
                    'size' => $size,
                    'style' => $style,
                    'color_theme' => $color_theme,
                    'flowers' => $_POST['flowers'] ?? [],
                    'total_price' => $total_price,
                    'message' => $message,
                    'is_custom' => true
                ];
                
                $_SESSION['success'] = 'Custom bouquet created and added to cart!';
                redirect('app.php?action=cart');
            } catch (Exception $e) {
                $_SESSION['error'] = 'Error saving custom bouquet. Please try again.';
                redirect('app.php?action=bouquet_builder');
            }
        }
        break;
        
    case 'products':
        if (!isset($_SESSION['is_logged_in'])) {
            redirect('app.php?action=login');
        }
        
        $db = SimpleDB::getInstance();
        
        if ($_SESSION['user_role'] === 'admin') {
            // Admin gets full product management
            if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
                // Edit product form
                $product_id = (int)$_GET['edit'];
                $result = $db->query("SELECT * FROM products WHERE id = $product_id");
                $product = $result->fetch_assoc();
                
                // Get categories
                $result = $db->query("SELECT * FROM categories ORDER BY name");
                $categories = [];
                while ($row = $result->fetch_assoc()) {
                    $categories[] = $row;
                }
                
                echo view('simple_product_edit', [
                    'product' => $product,
                    'categories' => $categories,
                    'page_title' => 'Edit Product'
                ]);
            } elseif (isset($_GET['add'])) {
                // Add product form
                $result = $db->query("SELECT * FROM categories ORDER BY name");
                $categories = [];
                while ($row = $result->fetch_assoc()) {
                    $categories[] = $row;
                }
                
                echo view('simple_product_add', [
                    'categories' => $categories,
                    'page_title' => 'Add Product'
                ]);
            } else {
                // Admin product list
                try {
                    $result = $db->query("SELECT * FROM products ORDER BY name");
                    
                    $products = [];
                    while ($row = $result->fetch_assoc()) {
                        $products[] = $row;
                    }
                    
                    echo view('simple_admin_products', [
                        'products' => $products,
                        'page_title' => 'Manage Products'
                    ]);
                } catch (Exception $e) {
                    $_SESSION['error'] = 'Error loading products. Please try again.';
                    redirect('app.php?action=admin');
                }
            }
        } else {
            // Customer gets product catalog
            try {
                $search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
                
                if (!empty($search_query)) {
                    // Search products by name, description, or category
                    $escaped_search = $db->escape($search_query);
                    $result = $db->query("SELECT * FROM products WHERE status = 'active' AND (name LIKE '%$escaped_search%' OR short_description LIKE '%$escaped_search%' OR description LIKE '%$escaped_search%') ORDER BY name");
                } else {
                    // Get all active products
                    $result = $db->query("SELECT * FROM products WHERE status = 'active' ORDER BY name");
                }
                
                $products = [];
                while ($row = $result->fetch_assoc()) {
                    $products[] = $row;
                }
                
                $page_title = !empty($search_query) ? "Search Results for '$search_query'" : 'Our Products';
                
                echo view('simple_products', [
                    'products' => $products,
                    'page_title' => $page_title,
                    'search_query' => $search_query
                ]);
            } catch (Exception $e) {
                $_SESSION['error'] = 'Error loading products. Please try again.';
                redirect('app.php?action=home');
            }
        }
        break;
        
    case 'save_product':
        if ($_SESSION['user_role'] !== 'admin') {
            redirect('app.php?action=login');
        }
        
        if ($_POST) {
            $db = SimpleDB::getInstance();
            
            $name = $db->escape($_POST['name']);
            $slug = $db->escape(strtolower(preg_replace('/[^a-z0-9]+/', '-', $_POST['name'])));
            $description = $db->escape($_POST['description']);
            $short_description = $db->escape($_POST['short_description']);
            $sku = $db->escape($_POST['sku']);
            $price = (float)$_POST['price'];
            $sale_price = !empty($_POST['sale_price']) ? (float)$_POST['sale_price'] : 'NULL';
            $stock_quantity = (int)$_POST['stock_quantity'];
            $min_stock_level = (int)$_POST['min_stock_level'];
            $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : 'NULL';
            $status = $db->escape($_POST['status']);
            $is_featured = isset($_POST['is_featured']) ? 1 : 0;
            
            // Handle image upload
            $images = 'NULL';
            if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
                $upload_dir = __DIR__ . '/uploads/products/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                $file_name = time() . '_' . basename($_FILES['product_image']['name']);
                $target_file = $upload_dir . $file_name;
                
                // Check file type
                $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                $file_type = mime_content_type($_FILES['product_image']['tmp_name']);
                
                if (in_array($file_type, $allowed_types)) {
                    if (move_uploaded_file($_FILES['product_image']['tmp_name'], $target_file)) {
                        $images = '"uploads/products/' . $file_name . '"';
                    }
                }
            }
            
            if (isset($_POST['product_id']) && is_numeric($_POST['product_id'])) {
                // Update existing product
                $product_id = (int)$_POST['product_id'];
                $sql = "UPDATE products SET 
                    name = '$name', 
                    slug = '$slug', 
                    description = '$description', 
                    short_description = '$short_description', 
                    sku = '$sku', 
                    price = $price, 
                    sale_price = $sale_price, 
                    stock_quantity = $stock_quantity, 
                    min_stock_level = $min_stock_level, 
                    category_id = $category_id, 
                    status = '$status', 
                    is_featured = $is_featured,
                    updated_at = NOW()";
                    
                if ($images !== 'NULL') {
                    $sql .= ", images = $images";
                }
                
                $sql .= " WHERE id = $product_id";
                
                $db->query($sql);
                $_SESSION['success'] = 'Product updated successfully!';
            } else {
                // Insert new product
                $sql = "INSERT INTO products (name, slug, description, short_description, sku, price, sale_price, stock_quantity, min_stock_level, category_id, status, is_featured, images, created_at, updated_at) 
                        VALUES ('$name', '$slug', '$description', '$short_description', '$sku', $price, $sale_price, $stock_quantity, $min_stock_level, $category_id, '$status', $is_featured, $images, NOW(), NOW())";
                
                $db->query($sql);
                $_SESSION['success'] = 'Product added successfully!';
            }
            
            redirect('app.php?action=products');
        }
        break;
        
    case 'delete_product':
        if ($_SESSION['user_role'] !== 'admin') {
            redirect('app.php?action=login');
        }
        
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $db = SimpleDB::getInstance();
            $product_id = (int)$_GET['id'];
            
            // Check if product is in any orders
            $result = $db->query("SELECT COUNT(*) as count FROM order_items WHERE product_id = $product_id");
            $count = $result->fetch_assoc()['count'];
            
            if ($count > 0) {
                $_SESSION['error'] = 'Cannot delete product - it is associated with existing orders.';
            } else {
                $db->query("DELETE FROM products WHERE id = $product_id");
                $_SESSION['success'] = 'Product deleted successfully!';
            }
            
            redirect('app.php?action=products');
        }
        break;
        
    case 'about':
        echo view('simple_about', [
            'page_title' => 'About Fleur'
        ]);
        break;
        
    case 'contact':
        if ($_POST) {
            // Process contact form
            $_SESSION['success'] = 'Thank you for your message! We\'ll get back to you within 24 hours.';
            redirect('app.php?action=contact');
        } else {
            echo view('simple_contact', [
                'page_title' => 'Contact Fleur'
            ]);
        }
        break;
        
    case 'cart':
        // Allow viewing cart without login, but require login for checkout
        
        if ($_POST) {
            // Handle custom bouquet
            if (isset($_POST['custom_bouquet'])) {
                $custom_bouquet = $_POST['custom_bouquet'];
                $product_id = $custom_bouquet['product_id'];
                $quantity = 1;
                
                if (!isset($_SESSION['cart'])) {
                    $_SESSION['cart'] = [];
// ... (rest of the code remains the same)
                }
                
                // Store custom bouquet details
                $_SESSION['cart'][$product_id] = $quantity;
                $_SESSION['custom_bouquets'][$product_id] = $custom_bouquet;
                
                // Clear the custom bouquet session
                unset($_SESSION['custom_bouquet']);
                
                $_SESSION['success'] = 'Custom bouquet added to cart!';
                redirect('app.php?action=cart');
            } else {
                // Regular product
                $product_id = (int)$_POST['product_id'];
                $quantity = (int)$_POST['quantity'];
                
                if (!isset($_SESSION['cart'])) {
                    $_SESSION['cart'] = [];
                }
                
                if (isset($_SESSION['cart'][$product_id])) {
                    $_SESSION['cart'][$product_id] += $quantity;
                } else {
                    $_SESSION['cart'][$product_id] = $quantity;
                }
                
                $_SESSION['success'] = 'Product added to cart!';
                redirect('app.php?action=cart');
            }
        } else {
            // View cart
            $cart_items = [];
            $total = 0;
            
            if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                $db = SimpleDB::getInstance();
                foreach ($_SESSION['cart'] as $item_id => $quantity) {
                    // Check if this is a custom bouquet
                    if (isset($_SESSION['custom_bouquets'][$item_id]) && $_SESSION['custom_bouquets'][$item_id]['is_custom']) {
                        // Handle custom bouquet from session
                        $custom_bouquet = $_SESSION['custom_bouquets'][$item_id];
                        
                        // Create a pseudo-product object for the cart
                        $pseudo_product = [
                            'id' => $item_id,
                            'name' => $custom_bouquet['name'],
                            'description' => 'Custom bouquet: ' . $custom_bouquet['size'] . ' ' . $custom_bouquet['style'] . ' arrangement',
                            'short_description' => 'Size: ' . ucfirst($custom_bouquet['size']) . ', Style: ' . ucfirst($custom_bouquet['style']),
                            'price' => $custom_bouquet['total_price'],
                            'sale_price' => null,
                            'images' => null,
                            'sku' => $custom_bouquet['custom_id']
                        ];
                        
                        $cart_items[] = [
                            'product' => $pseudo_product,
                            'quantity' => $quantity,
                            'subtotal' => $custom_bouquet['total_price'] * $quantity,
                            'is_custom' => true
                        ];
                        $total += $custom_bouquet['total_price'] * $quantity;
                    } else {
                        // Handle regular product from database
                        $result = $db->query("SELECT * FROM products WHERE id = $item_id AND status = 'active'");
                        $product = $result->fetch_assoc();
                        
                        if ($product) {
                            $cart_items[] = [
                                'product' => $product,
                                'quantity' => $quantity,
                                'subtotal' => $product['sale_price'] ? $product['sale_price'] * $quantity : $product['price'] * $quantity,
                                'is_custom' => false
                            ];
                            $total += $cart_items[count($cart_items) - 1]['subtotal'];
                        }
                    }
                }
            }
            
            echo view('simple_cart', [
                'cart_items' => $cart_items,
                'total' => $total,
                'page_title' => 'Shopping Cart'
            ]);
        }
        break;
        
    case 'remove_from_cart':
        if (!isset($_SESSION['is_logged_in'])) {
            redirect('app.php?action=login');
        }
        
        if (isset($_GET['product_id']) && is_numeric($_GET['product_id'])) {
            $product_id = (int)$_GET['product_id'];
            unset($_SESSION['cart'][$product_id]);
            $_SESSION['success'] = 'Item removed from cart!';
            redirect('app.php?action=cart');
        }
        break;
        
    case 'checkout':
        if (!isset($_SESSION['is_logged_in'])) {
            redirect('app.php?action=login');
        }
        
        if (empty($_SESSION['cart'])) {
            redirect('app.php?action=cart');
        }
        
        if ($_POST) {
            try {
                // Process order
                $db = SimpleDB::getInstance();
                
                // Generate order number
                $order_number = 'ORD-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            
            // Calculate total
            $total = 0;
            foreach ($_SESSION['cart'] as $item_id => $quantity) {
                // Check if this is a custom bouquet
                if (isset($_SESSION['custom_bouquets'][$item_id]) && $_SESSION['custom_bouquets'][$item_id]['is_custom']) {
                    // Handle custom bouquet from session
                    $custom_bouquet = $_SESSION['custom_bouquets'][$item_id];
                    $total += $custom_bouquet['total_price'] * $quantity;
                } else {
                    // Handle regular product from database
                    $result = $db->query("SELECT * FROM products WHERE id = $item_id");
                    $product = $result->fetch_assoc();
                    if ($product) {
                        $price = $product['sale_price'] ?: $product['price'];
                        $total += $price * $quantity;
                    }
                }
            }
            
            // Get customer contact and shipping details
            $first_name = $db->escape($_POST['first_name'] ?? '');
            $last_name = $db->escape($_POST['last_name'] ?? '');
            $email = $db->escape($_POST['email'] ?? '');
            $phone = $db->escape($_POST['phone'] ?? '');
            $shipping_address = $db->escape($_POST['shipping_address'] ?? '');
            $city = $db->escape($_POST['city'] ?? '');
            $state = $db->escape($_POST['state'] ?? '');
            $postal_code = $db->escape($_POST['postal_code'] ?? '');
            $payment_method = $db->escape($_POST['payment_method'] ?? 'cod');
            $delivery_date = $db->escape($_POST['delivery_date'] ?? '');
            $gift_message = $db->escape($_POST['gift_message'] ?? '');
            $customer_notes = $db->escape($_POST['order_notes'] ?? '');
            
            // Combine shipping information
            $full_shipping_address = $shipping_address . "\n" . $city . ", " . $state . " " . $postal_code;
            
            // Set payment status based on method
            $payment_status = ($payment_method === 'cod') ? 'pending' : 'pending';
            
            // Store customer name from checkout form in shipping address notes for now
            $customer_name_full = $first_name . ' ' . $last_name;
            $shipping_with_name = "Customer: $customer_name_full\nEmail: $email\nPhone: $phone\n\n" . $full_shipping_address;
            
            $sql = "INSERT INTO orders (order_number, customer_id, status, payment_status, payment_method, subtotal, tax_amount, shipping_amount, discount_amount, total_amount, currency, shipping_address, customer_notes, created_at, updated_at) 
                    VALUES ('$order_number', " . $_SESSION['user_id'] . ", 'pending', '$payment_status', '$payment_method', $total, 0.00, 0.00, 0.00, $total, 'PHP', '$shipping_with_name', '$customer_notes', NOW(), NOW())";
            
            if ($db->query($sql)) {
                $order_id = $db->insertId();
                
                if (!$order_id) {
                    $_SESSION['error'] = 'Failed to create order - please try again.';
                    redirect('app.php?action=cart');
                }
                
                // Insert order items
                $order_items_success = true;
                foreach ($_SESSION['cart'] as $item_id => $quantity) {
                    // Check if this is a custom bouquet
                    if (isset($_SESSION['custom_bouquets'][$item_id]) && $_SESSION['custom_bouquets'][$item_id]['is_custom']) {
                        // Handle custom bouquet from session
                        $custom_bouquet = $_SESSION['custom_bouquets'][$item_id];
                        $custom_details = json_encode([
                            'size' => $custom_bouquet['size'],
                            'style' => $custom_bouquet['style'],
                            'color_theme' => $custom_bouquet['color_theme'],
                            'message' => $custom_bouquet['message'],
                            'flowers' => $custom_bouquet['flowers']
                        ]);
                        $price = $custom_bouquet['total_price'];
                        $product_name = $custom_bouquet['name'];
                        $product_sku = $custom_bouquet['custom_id'];
                        $product_id_for_db = 0; // Use 0 for custom items
                    } else {
                        // Handle regular product from database
                        $result = $db->query("SELECT * FROM products WHERE id = $item_id");
                        $product = $result->fetch_assoc();
                        
                        if (!$product) {
                            $order_items_success = false;
                            break;
                        }
                        
                        $custom_details = '';
                        $price = $product['sale_price'] ?: $product['price'];
                        $product_name = $product['name'];
                        $product_sku = $product['sku'];
                        $product_id_for_db = $item_id;
                    }
                    
                    $sql = "INSERT INTO order_items (order_id, product_id, quantity, unit_price, total_price, product_name, product_sku, custom_bouquet_details, created_at) 
                            VALUES ($order_id, $product_id_for_db, $quantity, $price, " . ($price * $quantity) . ", '" . $db->escape($product_name) . "', '" . $db->escape($product_sku) . "', '" . $db->escape($custom_details) . "', NOW())";
                    
                    if (!$db->query($sql)) {
                        $order_items_success = false;
                        break;
                    }
                    
                    // Update stock (skip for custom bouquets as they're made to order)
                    if (!isset($_SESSION['custom_bouquets'][$item_id])) {
                        $new_stock = $product['stock_quantity'] - $quantity;
                        if (!$db->query("UPDATE products SET stock_quantity = $new_stock WHERE id = $item_id")) {
                            $order_items_success = false;
                            break;
                        }
                    }
                }
                
                if (!$order_items_success) {
                    // Rollback: delete the order if items failed
                    $db->query("DELETE FROM orders WHERE id = $order_id");
                    $_SESSION['error'] = 'Error processing order items. Please try again.';
                    redirect('app.php?action=cart');
                }
                
                // Clear cart and custom bouquets
                unset($_SESSION['cart']);
                unset($_SESSION['custom_bouquets']);
                
                $_SESSION['success'] = 'Order placed successfully! Order #' . $order_number;
                redirect('app.php?action=home');
            } else {
                $_SESSION['error'] = 'Database error - please try again.';
                redirect('app.php?action=cart');
            }
            } catch (Exception $e) {
                $_SESSION['error'] = 'An error occurred while processing your order. Please try again.';
                error_log("Checkout error: " . $e->getMessage());
                redirect('app.php?action=cart');
            }
        } else {
            // Get cart items for checkout
            $cart_items = [];
            $total = 0;
            
            $db = SimpleDB::getInstance();
            foreach ($_SESSION['cart'] as $item_id => $quantity) {
                // Check if this is a custom bouquet
                if (isset($_SESSION['custom_bouquets'][$item_id]) && $_SESSION['custom_bouquets'][$item_id]['is_custom']) {
                    // Handle custom bouquet from session
                    $custom_bouquet = $_SESSION['custom_bouquets'][$item_id];
                    
                    // Create a pseudo-product object for the checkout
                    $pseudo_product = [
                        'id' => $item_id,
                        'name' => $custom_bouquet['name'],
                        'description' => 'Custom bouquet: ' . $custom_bouquet['size'] . ' ' . $custom_bouquet['style'] . ' arrangement',
                        'short_description' => 'Size: ' . ucfirst($custom_bouquet['size']) . ', Style: ' . ucfirst($custom_bouquet['style']),
                        'price' => $custom_bouquet['total_price'],
                        'sale_price' => null,
                        'images' => null,
                        'sku' => $custom_bouquet['custom_id']
                    ];
                    
                    $cart_items[] = [
                        'product' => $pseudo_product,
                        'quantity' => $quantity,
                        'subtotal' => $custom_bouquet['total_price'] * $quantity,
                        'is_custom' => true
                    ];
                    $total += $custom_bouquet['total_price'] * $quantity;
                } else {
                    // Handle regular product from database
                    $result = $db->query("SELECT * FROM products WHERE id = $item_id AND status = 'active'");
                    $product = $result->fetch_assoc();
                    
                    if ($product) {
                        $cart_items[] = [
                            'product' => $product,
                            'quantity' => $quantity,
                            'subtotal' => $product['sale_price'] ? $product['sale_price'] * $quantity : $product['price'] * $quantity,
                            'is_custom' => false
                        ];
                        $total += $cart_items[count($cart_items) - 1]['subtotal'];
                    }
                }
            }
            
            // Get user info
            $result = $db->query("SELECT * FROM users WHERE id = " . $_SESSION['user_id']);
            $user = $result->fetch_assoc();
            
            echo view('simple_checkout', [
                'cart_items' => $cart_items,
                'total' => $total,
                'user' => $user,
                'page_title' => 'Checkout'
            ]);
        }
        break;
        
    case 'customers':
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['user_role'] !== 'admin') {
            redirect('app.php?action=login');
        }
        
        try {
            $db = SimpleDB::getInstance();
            
            // Debug: Test database connection
            if (!$db) {
                throw new Exception("Database connection failed");
            }
            
            if (isset($_GET['view']) && is_numeric($_GET['view'])) {
                // View customer details
                $customer_id = (int)$_GET['view'];
                $result = $db->query("SELECT * FROM users WHERE id = $customer_id AND role = 'customer'");
                $customer = $result->fetch_assoc();
                
                // Get customer orders
                $result = $db->query("SELECT * FROM orders WHERE customer_id = $customer_id ORDER BY created_at DESC");
                $orders = [];
                while ($row = $result->fetch_assoc()) {
                    $orders[] = $row;
                }
                
                echo view('simple_customer_view', [
                    'customer' => $customer,
                    'orders' => $orders,
                    'page_title' => 'Customer Details'
                ]);
            } else {
                // Customer list
                $result = $db->query("SELECT * FROM users WHERE role = 'customer' ORDER BY created_at DESC");
                $customers = [];
                while ($row = $result->fetch_assoc()) {
                    $customers[] = $row;
                }
                
                // Debug: Log customer count
                error_log("Customer count: " . count($customers));
                
                echo view('simple_customers', [
                    'customers' => $customers,
                    'page_title' => 'Manage Customers'
                ]);
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error loading customer data. Please try again.';
            redirect('app.php?action=admin');
        }
        break;
        
    case 'inventory':
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['user_role'] !== 'admin') {
            redirect('app.php?action=login');
        }
        
        try {
            $db = SimpleDB::getInstance();
            
            // Get products with stock information (simplified)
            $result = $db->query("SELECT * FROM products ORDER BY stock_quantity ASC");
            $inventory = [];
            while ($row = $result->fetch_assoc()) {
                $inventory[] = $row;
            }
            
            // Get low stock items
            $result = $db->query("SELECT * FROM products WHERE stock_quantity <= min_stock_level ORDER BY stock_quantity ASC");
            $low_stock = [];
            while ($row = $result->fetch_assoc()) {
                $low_stock[] = $row;
            }
            
            // Debug: Log inventory counts
            error_log("Inventory count: " . count($inventory));
            error_log("Low stock count: " . count($low_stock));
            
            echo view('simple_inventory', [
                'inventory' => $inventory,
                'low_stock' => $low_stock,
                'page_title' => 'Inventory Management'
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error loading inventory data. Please try again.';
            redirect('app.php?action=admin');
        }
        break;
        
    case 'edit_inventory':
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['user_role'] !== 'admin') {
            redirect('app.php?action=login');
        }
        
        $product_id = $_GET['id'] ?? '';
        if (!$product_id) {
            $_SESSION['error'] = 'Product ID required';
            redirect('app.php?action=inventory');
        }
        
        $db = SimpleDB::getInstance();
        $product_id = (int)$product_id;
        
        if ($_POST) {
            // Update inventory
            $stock_quantity = (int)$_POST['stock_quantity'];
            $min_stock_level = (int)$_POST['min_stock_level'];
            $price = (float)$_POST['price'];
            $sale_price = !empty($_POST['sale_price']) ? (float)$_POST['sale_price'] : null;
            
            $sql = "UPDATE products SET 
                    stock_quantity = $stock_quantity,
                    min_stock_level = $min_stock_level,
                    price = $price,
                    sale_price = " . ($sale_price ? $sale_price : 'NULL') . ",
                    updated_at = NOW()
                    WHERE id = $product_id";
            
            if ($db->query($sql)) {
                $_SESSION['success'] = 'Inventory updated successfully!';
                redirect('app.php?action=inventory');
            } else {
                $_SESSION['error'] = 'Error updating inventory. Please try again.';
                redirect('app.php?action=edit_inventory&id=' . $product_id);
            }
        } else {
            // Get product details
            $result = $db->query("SELECT * FROM products WHERE id = $product_id");
            $product = $result->fetch_assoc();
            
            if (!$product) {
                $_SESSION['error'] = 'Product not found';
                redirect('app.php?action=inventory');
            }
            
            echo view('simple_edit_inventory', [
                'product' => $product,
                'page_title' => 'Edit Inventory'
            ]);
        }
        break;
        
    case 'reports':
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['user_role'] !== 'admin') {
            redirect('app.php?action=login');
        }
        
        try {
            $db = SimpleDB::getInstance();
            
            // Get report data
            $reports = [
                'total_orders' => $db->query("SELECT COUNT(*) as count FROM orders")->fetch_assoc()['count'],
                'total_revenue' => $db->query("SELECT SUM(total_amount) as total FROM orders")->fetch_assoc()['total'] ?? 0,
                'total_customers' => $db->query("SELECT COUNT(*) as count FROM users WHERE role = 'customer'")->fetch_assoc()['count'],
                'total_products' => $db->query("SELECT COUNT(*) as count FROM products")->fetch_assoc()['count'],
                'pending_orders' => $db->query("SELECT COUNT(*) as count FROM orders WHERE status = 'pending'")->fetch_assoc()['count'],
                'completed_orders' => $db->query("SELECT COUNT(*) as count FROM orders WHERE status = 'delivered'")->fetch_assoc()['count'],
            ];
            
            // Get recent orders for report
            $result = $db->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 10");
            $recent_orders = [];
            while ($row = $result->fetch_assoc()) {
                $recent_orders[] = $row;
            }
            
            // Debug: Log report data
            error_log("Reports data: " . json_encode($reports));
            error_log("Recent orders count: " . count($recent_orders));
            
            echo view('simple_reports', [
                'reports' => $reports,
                'recent_orders' => $recent_orders,
                'page_title' => 'Reports & Analytics'
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error loading report data. Please try again.';
            redirect('app.php?action=admin');
        }
        break;
        
    default:
        redirect('app.php?action=home');
        break;
}
?>
