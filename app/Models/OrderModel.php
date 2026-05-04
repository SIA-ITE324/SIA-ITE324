<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\I18n\Time;

class OrderModel extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'order_number',
        'customer_id',
        'status',
        'payment_status',
        'payment_method',
        'subtotal',
        'tax_amount',
        'shipping_amount',
        'discount_amount',
        'total_amount',
        'currency',
        'shipping_address',
        'billing_address',
        'customer_notes',
        'admin_notes',
        'tracking_number',
        'shipping_method',
        'estimated_delivery',
        'actual_delivery',
        'assigned_driver'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'datetime';

    protected $validationRules = [
        'customer_id' => 'required|integer|is_not_unique[users.id]',
        'status' => 'required|in_list[pending,confirmed,processing,shipped,delivered,cancelled,refunded]',
        'payment_status' => 'required|in_list[pending,paid,failed,refunded]',
        'payment_method' => 'required|in_list[cod,bank_transfer,credit_card,paypal]',
        'subtotal' => 'required|numeric|greater_than_equal[0]',
        'tax_amount' => 'required|numeric|greater_than_equal[0]',
        'shipping_amount' => 'required|numeric|greater_than_equal[0]',
        'discount_amount' => 'required|numeric|greater_than_equal[0]',
        'total_amount' => 'required|numeric|greater_than[0]',
        'shipping_address' => 'required',
    ];

    protected $skipValidation = false;

    /**
     * Create new order
     */
    public function createOrder($orderData, $orderItems)
    {
        $db = \Config\Database::connect();
        
        try {
            $db->transStart();

            // Generate unique order number
            $orderData['order_number'] = $this->generateOrderNumber();

            // Insert order
            $orderId = $this->insert($orderData);

            if (!$orderId) {
                throw new \Exception('Failed to create order');
            }

            // Insert order items
            $orderItemModel = new OrderItemModel();
            
            foreach ($orderItems as $item) {
                $item['order_id'] = $orderId;
                $orderItemModel->insert($item);

                // Update product stock
                $productModel = new ProductModel();
                $productModel->updateStock($item['product_id'], $item['quantity'], 'sale', $orderId);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Transaction failed');
            }

            return $orderId;
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Order creation failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get order with details
     */
    public function getOrderWithDetails($orderId)
    {
        $db = \Config\Database::connect();
        
        $query = $db->query("
            SELECT o.*, 
                   CONCAT(u.first_name, ' ', u.last_name) as customer_name,
                   u.email as customer_email,
                   u.phone as customer_phone
            FROM orders o
            LEFT JOIN users u ON o.customer_id = u.id
            WHERE o.id = ?
        ", [$orderId]);

        $order = $query->getRowArray();

        if ($order) {
            // Get order items
            $orderItemModel = new OrderItemModel();
            $order['items'] = $orderItemModel->getOrderItems($orderId);
        }

        return $order;
    }

    /**
     * Get orders by customer
     */
    public function getCustomerOrders($customerId, $page = 1, $perPage = 10)
    {
        $builder = $this->where('customer_id', $customerId)
                       ->orderBy('created_at', 'DESC');

        $total = $builder->countAllResults(false);
        $offset = ($page - 1) * $perPage;
        
        $orders = $builder->limit($perPage, $offset)->findAll();

        return [
            'data' => $orders,
            'pagination' => $this->getPaginationData($total, $perPage, $page),
        ];
    }

    /**
     * Get all orders with filtering
     */
    public function getAllOrders($status = null, $paymentStatus = null, $dateFrom = null, $dateTo = null, $page = 1, $perPage = 20)
    {
        $builder = $this->builder();

        if ($status) {
            $builder->where('status', $status);
        }

        if ($paymentStatus) {
            $builder->where('payment_status', $paymentStatus);
        }

        if ($dateFrom) {
            $builder->where('created_at >=', $dateFrom . ' 00:00:00');
        }

        if ($dateTo) {
            $builder->where('created_at <=', $dateTo . ' 23:59:59');
        }

        $builder->orderBy('created_at', 'DESC');

        $total = $builder->countAllResults(false);
        $offset = ($page - 1) * $perPage;
        
        $orders = $builder->limit($perPage, $offset)->get()->getResultArray();

        // Add customer details
        foreach ($orders as &$order) {
            $customer = $this->getCustomerDetails($order['customer_id']);
            $order['customer_name'] = $customer ? $customer['name'] : 'Unknown';
            $order['customer_email'] = $customer ? $customer['email'] : 'Unknown';
        }

        return [
            'data' => $orders,
            'pagination' => $this->getPaginationData($total, $perPage, $page),
        ];
    }

    /**
     * Update order status
     */
    public function updateStatus($orderId, $status, $notes = null)
    {
        $order = $this->find($orderId);
        
        if (!$order) {
            return false;
        }

        $oldStatus = $order['status'];
        
        $updateData = ['status' => $status];
        
        if ($notes) {
            $updateData['admin_notes'] = $notes;
        }

        // Set actual delivery date if status is delivered
        if ($status === 'delivered' && !$order['actual_delivery']) {
            $updateData['actual_delivery'] = Time::now();
        }

        // Update order
        $result = $this->update($orderId, $updateData);

        if ($result) {
            // Log activity
            $this->logActivity('status_update', 'order', $orderId, "Order status changed from {$oldStatus} to {$status}");
            
            // Send notification to customer
            $this->sendStatusNotification($orderId, $status);
        }

        return $result;
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus($orderId, $paymentStatus)
    {
        $order = $this->find($orderId);
        
        if (!$order) {
            return false;
        }

        $oldStatus = $order['payment_status'];
        $result = $this->update($orderId, ['payment_status' => $paymentStatus]);

        if ($result) {
            // Log activity
            $this->logActivity('payment_status_update', 'order', $orderId, "Payment status changed from {$oldStatus} to {$paymentStatus}");
            
            // Send notification to customer
            $this->sendPaymentStatusNotification($orderId, $paymentStatus);
        }

        return $result;
    }

    /**
     * Cancel order
     */
    public function cancelOrder($orderId, $reason = null)
    {
        $order = $this->find($orderId);
        
        if (!$order) {
            return false;
        }

        // Check if order can be cancelled
        if (in_array($order['status'], ['shipped', 'delivered'])) {
            return false;
        }

        $db = \Config\Database::connect();
        
        try {
            $db->transStart();

            // Update order status
            $this->update($orderId, [
                'status' => 'cancelled',
                'admin_notes' => $reason ? "Cancelled: {$reason}" : 'Cancelled'
            ]);

            // Restore product stock
            $orderItemModel = new OrderItemModel();
            $items = $orderItemModel->getOrderItems($orderId);
            
            $productModel = new ProductModel();
            foreach ($items as $item) {
                $productModel->updateStock($item['product_id'], $item['quantity'], 'return', $orderId);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Transaction failed');
            }

            // Log activity
            $this->logActivity('cancel', 'order', $orderId, "Order cancelled: {$reason}");

            // Send notification to customer
            $this->sendCancellationNotification($orderId, $reason);

            return true;
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Order cancellation failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate order number
     */
    private function generateOrderNumber()
    {
        $prefix = 'FLEUR';
        $timestamp = date('Ymd');
        $random = mt_rand(1000, 9999);
        
        $orderNumber = $prefix . $timestamp . $random;

        // Check if order number already exists
        while ($this->where('order_number', $orderNumber)->first()) {
            $random = mt_rand(1000, 9999);
            $orderNumber = $prefix . $timestamp . $random;
        }

        return $orderNumber;
    }

    /**
     * Get customer details
     */
    private function getCustomerDetails($customerId)
    {
        $db = \Config\Database::connect();
        $query = $db->query("
            SELECT CONCAT(first_name, ' ', last_name) as name, email
            FROM users
            WHERE id = ?
        ", [$customerId]);

        return $query->getRowArray();
    }

    /**
     * Get order statistics
     */
    public function getOrderStats($dateFrom = null, $dateTo = null)
    {
        $db = \Config\Database::connect();
        
        $whereClause = "";
        $params = [];
        
        if ($dateFrom) {
            $whereClause .= " AND created_at >= ?";
            $params[] = $dateFrom . ' 00:00:00';
        }
        
        if ($dateTo) {
            $whereClause .= " AND created_at <= ?";
            $params[] = $dateTo . ' 23:59:59';
        }

        $query = $db->query("
            SELECT 
                COUNT(*) as total_orders,
                COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_orders,
                COUNT(CASE WHEN status = 'confirmed' THEN 1 END) as confirmed_orders,
                COUNT(CASE WHEN status = 'processing' THEN 1 END) as processing_orders,
                COUNT(CASE WHEN status = 'shipped' THEN 1 END) as shipped_orders,
                COUNT(CASE WHEN status = 'delivered' THEN 1 END) as delivered_orders,
                COUNT(CASE WHEN status = 'cancelled' THEN 1 END) as cancelled_orders,
                COUNT(CASE WHEN payment_status = 'paid' THEN 1 END) as paid_orders,
                COUNT(CASE WHEN payment_status = 'pending' THEN 1 END) as unpaid_orders,
                COALESCE(SUM(total_amount), 0) as total_revenue,
                COALESCE(AVG(total_amount), 0) as avg_order_value
            FROM orders
            WHERE 1=1 {$whereClause}
        ", $params);

        return $query->getRowArray();
    }

    /**
     * Get order tracking information
     */
    public function getOrderTracking($orderNumber)
    {
        $order = $this->where('order_number', $orderNumber)->first();
        
        if (!$order) {
            return null;
        }

        return [
            'order_number' => $order['order_number'],
            'status' => $order['status'],
            'tracking_number' => $order['tracking_number'],
            'estimated_delivery' => $order['estimated_delivery'],
            'actual_delivery' => $order['actual_delivery'],
            'created_at' => $order['created_at'],
            'updated_at' => $order['updated_at'],
        ];
    }

    /**
     * Search orders
     */
    public function searchOrders($searchTerm, $page = 1, $perPage = 20)
    {
        $builder = $this->builder();

        if ($searchTerm) {
            $builder->groupStart()
                   ->like('order_number', $searchTerm)
                   ->orLike('tracking_number', $searchTerm)
                   ->groupEnd();
        }

        $builder->orderBy('created_at', 'DESC');

        $total = $builder->countAllResults(false);
        $offset = ($page - 1) * $perPage;
        
        $orders = $builder->limit($perPage, $offset)->get()->getResultArray();

        // Add customer details
        foreach ($orders as &$order) {
            $customer = $this->getCustomerDetails($order['customer_id']);
            $order['customer_name'] = $customer ? $customer['name'] : 'Unknown';
            $order['customer_email'] = $customer ? $customer['email'] : 'Unknown';
        }

        return [
            'data' => $orders,
            'pagination' => $this->getPaginationData($total, $perPage, $page),
        ];
    }

    /**
     * Send status notification
     */
    private function sendStatusNotification($orderId, $status)
    {
        // TODO: Implement email notification using Brevo
        $order = $this->getOrderWithDetails($orderId);
        
        if ($order) {
            $subject = "Order Status Update - {$order['order_number']}";
            $message = "Your order status has been updated to: {$status}";
            
            // This will be implemented with Brevo integration
            log_message('info', "Status notification sent for order {$orderId}: {$status}");
        }
    }

    /**
     * Send payment status notification
     */
    private function sendPaymentStatusNotification($orderId, $paymentStatus)
    {
        // TODO: Implement email notification using Brevo
        $order = $this->getOrderWithDetails($orderId);
        
        if ($order) {
            $subject = "Payment Status Update - {$order['order_number']}";
            $message = "Your payment status has been updated to: {$paymentStatus}";
            
            // This will be implemented with Brevo integration
            log_message('info', "Payment status notification sent for order {$orderId}: {$paymentStatus}");
        }
    }

    /**
     * Send cancellation notification
     */
    private function sendCancellationNotification($orderId, $reason)
    {
        // TODO: Implement email notification using Brevo
        $order = $this->getOrderWithDetails($orderId);
        
        if ($order) {
            $subject = "Order Cancelled - {$order['order_number']}";
            $message = "Your order has been cancelled. Reason: {$reason}";
            
            // This will be implemented with Brevo integration
            log_message('info', "Cancellation notification sent for order {$orderId}: {$reason}");
        }
    }

    /**
     * Log activity
     */
    private function logActivity($action, $entityType, $entityId, $description)
    {
        $db = \Config\Database::connect();
        
        $data = [
            'user_id' => session()->get('user_id'),
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'description' => $description,
            'ip_address' => service('request')->getIPAddress(),
            'user_agent' => service('request')->getUserAgent(),
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $db->table('activity_logs')->insert($data);
    }

    /**
     * Enhanced search orders
     */
    public function searchOrdersAdvanced($searchTerm = null, $status = null, $paymentStatus = null, $dateFrom = null, $dateTo = null, $page = 1, $perPage = 20)
    {
        $builder = $this->builder();

        if ($searchTerm) {
            $builder->groupStart()
                   ->like('order_number', $searchTerm)
                   ->orLike('tracking_number', $searchTerm)
                   ->orGroupStart()
                       ->join('users', 'users.id = orders.customer_id', 'left')
                       ->like('CONCAT(users.first_name, " ", users.last_name)', $searchTerm)
                       ->orLike('users.email', $searchTerm)
                   ->groupEnd()
                   ->groupEnd();
        }

        if ($status) {
            $builder->where('status', $status);
        }

        if ($paymentStatus) {
            $builder->where('payment_status', $paymentStatus);
        }

        if ($dateFrom) {
            $builder->where('created_at >=', $dateFrom . ' 00:00:00');
        }

        if ($dateTo) {
            $builder->where('created_at <=', $dateTo . ' 23:59:59');
        }

        $builder->orderBy('created_at', 'DESC');

        $total = $builder->countAllResults(false);
        $offset = ($page - 1) * $perPage;
        
        $orders = $builder->limit($perPage, $offset)->get()->getResultArray();

        // Add customer details
        foreach ($orders as &$order) {
            $customer = $this->getCustomerDetails($order['customer_id']);
            $order['customer_name'] = $customer ? $customer['name'] : 'Unknown';
            $order['customer_email'] = $customer ? $customer['email'] : 'Unknown';
        }

        return [
            'data' => $orders,
            'pagination' => $this->getPaginationData($total, $perPage, $page),
        ];
    }

    /**
     * Get orders created since a specific date/time
     */
    public function getOrdersSince($dateTime)
    {
        return $this->where('created_at >=', $dateTime)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get enhanced order statistics for dashboard
     */
    public function getEnhancedOrderStats($dateFrom = null, $dateTo = null)
    {
        $db = \Config\Database::connect();
        
        $whereClause = "";
        $params = [];
        
        if ($dateFrom) {
            $whereClause .= " AND created_at >= ?";
            $params[] = $dateFrom . ' 00:00:00';
        }
        
        if ($dateTo) {
            $whereClause .= " AND created_at <= ?";
            $params[] = $dateTo . ' 23:59:59';
        }

        $today = date('Y-m-d');
        $todayWhere = "AND DATE(created_at) = ?";
        $todayParams = [$today];

        $query = $db->query("
            SELECT 
                COUNT(CASE WHEN DATE(created_at) = ? THEN 1 END) as today_orders,
                COALESCE(SUM(CASE WHEN DATE(created_at) = ? THEN total_amount END), 0) as today_sales,
                COUNT(CASE WHEN status IN ('confirmed', 'processing') THEN 1 END) as pending_deliveries,
                COUNT(CASE WHEN status = 'shipped' THEN 1 END) as out_for_delivery,
                COUNT(CASE WHEN status = 'delivered' AND DATE(created_at) = ? THEN 1 END) as completed_today,
                COUNT(CASE WHEN status = 'delivered' THEN 1 END) as total_delivered,
                COUNT(CASE WHEN status = 'cancelled' THEN 1 END) as total_cancelled
            FROM orders
            WHERE 1=1 {$todayWhere}
        ", array_merge($todayParams, $todayParams, $todayParams));

        $stats = $query->getRowArray();

        // Get most popular products this week
        $weekStart = date('Y-m-d', strtotime('-7 days'));
        $productQuery = $db->query("
            SELECT 
                oi.product_name,
                COUNT(*) as order_count,
                SUM(oi.quantity) as total_quantity
            FROM order_items oi
            JOIN orders o ON oi.order_id = o.id
            WHERE o.created_at >= ?
            GROUP BY oi.product_name
            ORDER BY total_quantity DESC
            LIMIT 5
        ", [$weekStart . ' 00:00:00']);

        $stats['popular_products'] = $productQuery->getResultArray();

        return $stats;
    }

    /**
     * Get orders for delivery mapping
     */
    public function getPendingDeliveryOrders()
    {
        return $this->select('orders.*, CONCAT(u.first_name, " ", u.last_name) as customer_name, u.phone')
                    ->join('users', 'users.id = orders.customer_id')
                    ->whereIn('status', ['confirmed', 'processing'])
                    ->orderBy('estimated_delivery', 'ASC')
                    ->findAll();
    }

    /**
     * Get pagination data
     */
    private function getPaginationData($total, $perPage, $currentPage)
    {
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
}
