<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\ProductModel;
use App\Models\UserModel;
use App\Models\CategoryModel;

class Dashboard extends BaseController
{
    protected $orderModel;
    protected $productModel;
    protected $userModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->productModel = new ProductModel();
        $this->userModel = new UserModel();
        $this->categoryModel = new CategoryModel();
    }

    /**
     * Display admin dashboard
     */
    public function index()
    {
        $this->requireRole('admin');

        // Get dashboard statistics
        $stats = $this->getDashboardStats();
        
        // Get recent orders
        $recentOrders = $this->orderModel->getAllOrders(null, null, null, null, 1, 5);
        
        // Get low stock products
        $lowStockProducts = $this->productModel->getLowStockProducts();
        
        // Get monthly sales data
        $monthlySales = $this->getMonthlySalesData();
        
        // Get top selling products
        $topProducts = $this->getTopSellingProducts();
        
        // Get recent customers
        $recentCustomers = $this->getRecentCustomers();

        $data = [
            'stats' => $stats,
            'recent_orders' => $recentOrders['data'],
            'low_stock_products' => $lowStockProducts,
            'monthly_sales' => $monthlySales,
            'top_products' => $topProducts,
            'recent_customers' => $recentCustomers,
            'page_title' => 'Admin Dashboard',
        ];

        return view('admin/dashboard/index', $data);
    }

    /**
     * Get comprehensive dashboard statistics
     */
    private function getDashboardStats()
    {
        $orderStats = $this->orderModel->getOrderStats();
        $productStats = $this->productModel->getProductStats();
        $userStats = $this->getUserStats();
        $categoryStats = $this->categoryModel->getCategoryStats();

        return [
            'orders' => $orderStats,
            'products' => $productStats,
            'users' => $userStats,
            'categories' => $categoryStats,
        ];
    }

    /**
     * Get monthly sales data for chart
     */
    private function getMonthlySalesData()
    {
        $orderItemModel = new \App\Models\OrderItemModel();
        return $orderItemModel->getMonthlySalesData();
    }

    /**
     * Get top selling products
     */
    private function getTopSellingProducts($limit = 5)
    {
        $orderItemModel = new \App\Models\OrderItemModel();
        return $orderItemModel->getTopSellingProducts($limit);
    }

    /**
     * Get recent customers
     */
    private function getRecentCustomers($limit = 5)
    {
        return $this->userModel->where('role', 'customer')
                              ->orderBy('created_at', 'DESC')
                              ->limit($limit)
                              ->findAll();
    }

    /**
     * Get user statistics
     */
    private function getUserStats()
    {
        $db = \Config\Database::connect();
        
        $query = $db->query("
            SELECT 
                COUNT(*) as total_users,
                COUNT(CASE WHEN role = 'customer' THEN 1 END) as total_customers,
                COUNT(CASE WHEN role = 'staff' THEN 1 END) as total_staff,
                COUNT(CASE WHEN role = 'admin' THEN 1 END) as total_admins,
                COUNT(CASE WHEN status = 'active' THEN 1 END) as active_users,
                COUNT(CASE WHEN status = 'inactive' THEN 1 END) as inactive_users,
                COUNT(CASE WHEN DATE(created_at) = CURDATE() THEN 1 END) as new_today,
                COUNT(CASE WHEN created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) THEN 1 END) as new_this_week,
                COUNT(CASE WHEN created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 1 END) as new_this_month
            FROM users
        ");

        return $query->getRowArray();
    }

    /**
     * Get sales overview data
     */
    public function salesOverview()
    {
        $this->requireRole('admin');

        $period = $this->request->getGet('period', 'month'); // day, week, month, year
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');

        // Set date range based on period
        if (!$dateFrom || !$dateTo) {
            switch ($period) {
                case 'day':
                    $dateFrom = date('Y-m-d');
                    $dateTo = date('Y-m-d');
                    break;
                case 'week':
                    $dateFrom = date('Y-m-d', strtotime('monday this week'));
                    $dateTo = date('Y-m-d', strtotime('sunday this week'));
                    break;
                case 'month':
                    $dateFrom = date('Y-m-01');
                    $dateTo = date('Y-m-t');
                    break;
                case 'year':
                    $dateFrom = date('Y-01-01');
                    $dateTo = date('Y-12-31');
                    break;
                default:
                    $dateFrom = date('Y-m-01');
                    $dateTo = date('Y-m-t');
            }
        }

        $orderStats = $this->orderModel->getOrderStats($dateFrom, $dateTo);
        
        // Get daily sales data for chart
        $dailySales = $this->getDailySalesData($dateFrom, $dateTo);

        return $this->response->setJSON([
            'stats' => $orderStats,
            'daily_sales' => $dailySales,
            'period' => $period,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
        ]);
    }

    /**
     * Get daily sales data
     */
    private function getDailySalesData($dateFrom, $dateTo)
    {
        $db = \Config\Database::connect();
        
        $query = $db->query("
            SELECT 
                DATE(created_at) as date,
                COUNT(*) as order_count,
                COALESCE(SUM(total_amount), 0) as total_revenue
            FROM orders
            WHERE created_at BETWEEN ? AND ?
            GROUP BY DATE(created_at)
            ORDER BY date
        ", [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);

        return $query->getResultArray();
    }

    /**
     * Get inventory overview
     */
    public function inventoryOverview()
    {
        $this->requireRole('admin');

        $productStats = $this->productModel->getProductStats();
        $lowStockProducts = $this->productModel->getLowStockProducts();
        
        // Get inventory by category
        $inventoryByCategory = $this->getInventoryByCategory();

        return $this->response->setJSON([
            'stats' => $productStats,
            'low_stock_products' => $lowStockProducts,
            'inventory_by_category' => $inventoryByCategory,
        ]);
    }

    /**
     * Get inventory by category
     */
    private function getInventoryByCategory()
    {
        $db = \Config\Database::connect();
        
        $query = $db->query("
            SELECT 
                c.name as category_name,
                COUNT(p.id) as product_count,
                COALESCE(SUM(p.stock_quantity), 0) as total_stock,
                COUNT(CASE WHEN p.stock_quantity <= p.min_stock_level THEN 1 END) as low_stock_count
            FROM categories c
            LEFT JOIN products p ON c.id = p.category_id
            WHERE p.status = 'active'
            GROUP BY c.id, c.name
            ORDER BY total_stock DESC
        ");

        return $query->getResultArray();
    }

    /**
     * Get customer overview
     */
    public function customerOverview()
    {
        $this->requireRole('admin');

        $userStats = $this->getUserStats();
        $recentCustomers = $this->getRecentCustomers(10);
        
        // Get customer registration trends
        $registrationTrends = $this->getCustomerRegistrationTrends();

        return $this->response->setJSON([
            'stats' => $userStats,
            'recent_customers' => $recentCustomers,
            'registration_trends' => $registrationTrends,
        ]);
    }

    /**
     * Get customer registration trends
     */
    private function getCustomerRegistrationTrends()
    {
        $db = \Config\Database::connect();
        
        $query = $db->query("
            SELECT 
                DATE(created_at) as date,
                COUNT(*) as registrations
            FROM users
            WHERE role = 'customer'
            AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            GROUP BY DATE(created_at)
            ORDER BY date
        ");

        return $query->getResultArray();
    }

    /**
     * Get system health status
     */
    public function systemHealth()
    {
        $this->requireRole('admin');

        $health = [
            'database' => $this->checkDatabaseHealth(),
            'storage' => $this->checkStorageHealth(),
            'performance' => $this->checkPerformanceHealth(),
            'security' => $this->checkSecurityHealth(),
        ];

        return $this->response->setJSON($health);
    }

    /**
     * Check database health
     */
    private function checkDatabaseHealth()
    {
        try {
            $db = \Config\Database::connect();
            $query = $db->query("SELECT 1")->getResult();
            
            return [
                'status' => 'healthy',
                'message' => 'Database connection is working properly',
                'response_time' => microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'],
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Database connection failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Check storage health
     */
    private function checkStorageHealth()
    {
        $uploadPath = WRITEPATH . 'uploads';
        $freeSpace = disk_free_space($uploadPath);
        $totalSpace = disk_total_space($uploadPath);
        $usedSpace = $totalSpace - $freeSpace;
        $usagePercent = ($usedSpace / $totalSpace) * 100;

        $status = 'healthy';
        if ($usagePercent > 90) {
            $status = 'critical';
        } elseif ($usagePercent > 80) {
            $status = 'warning';
        }

        return [
            'status' => $status,
            'usage_percent' => round($usagePercent, 2),
            'free_space_gb' => round($freeSpace / 1024 / 1024 / 1024, 2),
            'total_space_gb' => round($totalSpace / 1024 / 1024 / 1024, 2),
        ];
    }

    /**
     * Check performance health
     */
    private function checkPerformanceHealth()
    {
        $memoryUsage = memory_get_usage(true);
        $memoryLimit = ini_get('memory_limit');
        $memoryLimitBytes = $this->parseMemoryLimit($memoryLimit);
        $memoryUsagePercent = ($memoryUsage / $memoryLimitBytes) * 100;

        $status = 'healthy';
        if ($memoryUsagePercent > 90) {
            $status = 'critical';
        } elseif ($memoryUsagePercent > 80) {
            $status = 'warning';
        }

        return [
            'status' => $status,
            'memory_usage_percent' => round($memoryUsagePercent, 2),
            'memory_usage_mb' => round($memoryUsage / 1024 / 1024, 2),
            'memory_limit' => $memoryLimit,
        ];
    }

    /**
     * Check security health
     */
    private function checkSecurityHealth()
    {
        $issues = [];
        
        // Check if debug mode is on
        if (ENVIRONMENT === 'development') {
            $issues[] = 'Application is running in development mode';
        }

        // Check if database credentials are exposed
        if (getenv('database.default.password') === '') {
            $issues[] = 'Database password is empty';
        }

        $status = empty($issues) ? 'healthy' : 'warning';

        return [
            'status' => $status,
            'issues' => $issues,
        ];
    }

    /**
     * Parse memory limit string
     */
    private function parseMemoryLimit($memoryLimit)
    {
        $unit = strtolower(substr($memoryLimit, -1));
        $value = (int) substr($memoryLimit, 0, -1);

        switch ($unit) {
            case 'g':
                return $value * 1024 * 1024 * 1024;
            case 'm':
                return $value * 1024 * 1024;
            case 'k':
                return $value * 1024;
            default:
                return (int) $memoryLimit;
        }
    }

    /**
     * Get quick actions data
     */
    public function quickActions()
    {
        $this->requireRole('admin');

        $data = [
            'pending_orders' => $this->orderModel->where('status', 'pending')->countAllResults(),
            'low_stock_products' => count($this->productModel->getLowStockProducts()),
            'new_customers' => $this->userModel->where('role', 'customer')
                                            ->where('created_at >=', date('Y-m-d'))
                                            ->countAllResults(),
            'unpaid_orders' => $this->orderModel->where('payment_status', 'pending')->countAllResults(),
        ];

        return $this->response->setJSON($data);
    }
}
