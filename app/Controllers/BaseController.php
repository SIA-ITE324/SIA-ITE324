<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * To extend from this Controller, your controller must define the
 * $this->viewPath property in a way that reflects the location
 * of the view files you will be using.
 */
class BaseController extends Controller
{
    /**
     * Initialization of controller properties.
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param LoggerInterface $logger
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
    }

    /**
     * Check if user is logged in
     */
    protected function isLoggedIn()
    {
        return session()->get('is_logged_in') === true;
    }

    /**
     * Get current user ID
     */
    protected function getCurrentUserId()
    {
        return session()->get('user_id');
    }

    /**
     * Get current user role
     */
    protected function getCurrentUserRole()
    {
        return session()->get('user_role');
    }

    /**
     * Check if current user has specific role
     */
    protected function hasRole($role)
    {
        return $this->getCurrentUserRole() === $role;
    }

    /**
     * Check if current user is admin
     */
    protected function isAdmin()
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if current user is staff
     */
    protected function isStaff()
    {
        return $this->hasRole('staff');
    }

    /**
     * Check if current user is customer
     */
    protected function isCustomer()
    {
        return $this->hasRole('customer');
    }

    /**
     * Require authentication
     */
    protected function requireAuth()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('/login')->with('error', 'Please login to continue.');
        }
    }

    /**
     * Require specific role
     */
    protected function requireRole($role)
    {
        $this->requireAuth();
        
        if (!$this->hasRole($role)) {
            return redirect()->to('/login')->with('error', 'Access denied. Insufficient permissions.');
        }
    }

    /**
     * Log user activity
     */
    protected function logActivity($action, $entityType, $entityId, $description)
    {
        $db = \Config\Database::connect();
        
        $data = [
            'user_id' => $this->getCurrentUserId(),
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'description' => $description,
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent(),
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $db->table('activity_logs')->insert($data);
    }

    /**
     * Send email using Brevo
     */
    protected function sendEmail($to, $subject, $message, $template = null)
    {
        // TODO: Implement Brevo email integration
        // For now, just log the email
        log_message('info', "Email sent to: {$to}, Subject: {$subject}");
        
        return true;
    }

    /**
     * Generate unique order number
     */
    protected function generateOrderNumber()
    {
        $prefix = 'FLEUR';
        $timestamp = date('Ymd');
        $random = mt_rand(1000, 9999);
        
        return $prefix . $timestamp . $random;
    }

    /**
     * Format currency
     */
    protected function formatCurrency($amount, $currency = 'USD')
    {
        return number_format($amount, 2) . ' ' . $currency;
    }

    /**
     * Format date
     */
    protected function formatDate($date, $format = 'Y-m-d H:i:s')
    {
        if (!$date) {
            return 'N/A';
        }
        
        return date($format, strtotime($date));
    }

    /**
     * Get pagination data
     */
    protected function getPaginationData($total, $perPage = 10, $currentPage = null)
    {
        $currentPage = $currentPage ?? $this->request->getVar('page') ?? 1;
        $totalPages = ceil($total / $perPage);
        
        return [
            'current_page' => $currentPage,
            'per_page' => $perPage,
            'total' => $total,
            'total_pages' => $totalPages,
            'has_next' => $currentPage < $totalPages,
            'has_prev' => $currentPage > 1,
            'next_page' => $currentPage < $totalPages ? $currentPage + 1 : null,
            'prev_page' => $currentPage > 1 ? $currentPage - 1 : null,
        ];
    }

    /**
     * Handle file upload
     */
    protected function handleFileUpload($fieldName, $uploadPath, $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'], $maxSize = 2048)
    {
        $file = $this->request->getFile($fieldName);
        
        if (!$file || !$file->isValid()) {
            return null;
        }

        // Check file size
        if ($file->getSize() > $maxSize * 1024) {
            return null;
        }

        // Check file type
        $extension = $file->getExtension();
        if (!in_array(strtolower($extension), $allowedTypes)) {
            return null;
        }

        // Generate unique filename
        $filename = $file->getRandomName();

        // Move file to upload path
        if ($file->move($uploadPath, $filename)) {
            return $filename;
        }

        return null;
    }

    /**
     * Handle Excel/CSV import
     */
    protected function handleExcelImport($fieldName, $uploadPath)
    {
        $file = $this->request->getFile($fieldName);
        
        if (!$file || !$file->isValid()) {
            return null;
        }

        $extension = $file->getExtension();
        
        if (!in_array(strtolower($extension), ['xlsx', 'xls', 'csv'])) {
            return null;
        }

        $filename = $file->getRandomName();
        
        if ($file->move($uploadPath, $filename)) {
            return $uploadPath . '/' . $filename;
        }

        return null;
    }

    /**
     * Export data to Excel/CSV
     */
    protected function exportToExcel($data, $filename, $headers = [])
    {
        // TODO: Implement Excel export using PhpSpreadsheet
        // For now, just return CSV
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // Add headers
        if (!empty($headers)) {
            fputcsv($output, $headers);
        }
        
        // Add data
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        
        fclose($output);
        exit;
    }

    /**
     * Get dashboard statistics
     */
    protected function getDashboardStats()
    {
        $db = \Config\Database::connect();
        
        // Get total orders
        $totalOrders = $db->table('orders')->countAllResults();
        
        // Get total revenue
        $totalRevenue = $db->table('orders')
                          ->selectSum('total_amount')
                          ->where('payment_status', 'paid')
                          ->get()
                          ->getRow()
                          ->total_amount ?? 0;
        
        // Get total customers
        $totalCustomers = $db->table('users')
                            ->where('role', 'customer')
                            ->where('status', 'active')
                            ->countAllResults();
        
        // Get total products
        $totalProducts = $db->table('products')
                           ->where('status', 'active')
                           ->countAllResults();
        
        // Get low stock products
        $lowStockProducts = $db->table('products')
                              ->where('stock_quantity <= min_stock_level')
                              ->where('status', 'active')
                              ->countAllResults();
        
        // Get recent orders
        $recentOrders = $db->table('orders')
                          ->select('orders.*, users.first_name, users.last_name')
                          ->join('users', 'users.id = orders.customer_id')
                          ->orderBy('orders.created_at', 'DESC')
                          ->limit(5)
                          ->get()
                          ->getResultArray();
        
        return [
            'total_orders' => $totalOrders,
            'total_revenue' => $totalRevenue,
            'total_customers' => $totalCustomers,
            'total_products' => $totalProducts,
            'low_stock_products' => $lowStockProducts,
            'recent_orders' => $recentOrders,
        ];
    }
}
