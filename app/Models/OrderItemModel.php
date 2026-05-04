<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderItemModel extends Model
{
    protected $table = 'order_items';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price',
        'total_price',
        'product_name',
        'product_sku',
        'product_image',
        'product_options'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'datetime';

    protected $validationRules = [
        'order_id' => 'required|integer|is_not_unique[orders.id]',
        'product_id' => 'required|integer|is_not_unique[products.id]',
        'quantity' => 'required|integer|greater_than[0]',
        'unit_price' => 'required|numeric|greater_than[0]',
        'total_price' => 'required|numeric|greater_than[0]',
        'product_name' => 'required|string|max_length[200]',
        'product_sku' => 'required|string|max_length[100]',
    ];

    protected $skipValidation = false;

    /**
     * Get order items
     */
    public function getOrderItems($orderId)
    {
        return $this->where('order_id', $orderId)
                   ->orderBy('id', 'ASC')
                   ->findAll();
    }

    /**
     * Get order item with product details
     */
    public function getOrderItemWithProduct($orderItemId)
    {
        $db = \Config\Database::connect();
        
        $query = $db->query("
            SELECT oi.*, p.name as current_product_name, p.status as current_product_status
            FROM order_items oi
            LEFT JOIN products p ON oi.product_id = p.id
            WHERE oi.id = ?
        ", [$orderItemId]);

        return $query->getRowArray();
    }

    /**
     * Get product sales statistics
     */
    public function getProductSalesStats($productId, $dateFrom = null, $dateTo = null)
    {
        $builder = $this->builder()
                      ->select('
                          SUM(quantity) as total_sold,
                          SUM(total_price) as total_revenue,
                          COUNT(DISTINCT order_id) as order_count,
                          AVG(unit_price) as avg_price
                      ')
                      ->where('product_id', $productId);

        if ($dateFrom) {
            $builder->where('created_at >=', $dateFrom . ' 00:00:00');
        }

        if ($dateTo) {
            $builder->where('created_at <=', $dateTo . ' 23:59:59');
        }

        return $builder->get()->getRowArray();
    }

    /**
     * Get top selling products
     */
    public function getTopSellingProducts($limit = 10, $dateFrom = null, $dateTo = null)
    {
        $builder = $this->builder()
                      ->select('
                          product_id,
                          product_name,
                          product_sku,
                          SUM(quantity) as total_sold,
                          SUM(total_price) as total_revenue,
                          COUNT(DISTINCT order_id) as order_count
                      ')
                      ->groupBy('product_id, product_name, product_sku')
                      ->orderBy('total_sold', 'DESC')
                      ->limit($limit);

        if ($dateFrom) {
            $builder->where('created_at >=', $dateFrom . ' 00:00:00');
        }

        if ($dateTo) {
            $builder->where('created_at <=', $dateTo . ' 23:59:59');
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Get sales by category
     */
    public function getSalesByCategory($dateFrom = null, $dateTo = null)
    {
        $db = \Config\Database::connect();
        
        $whereClause = "";
        $params = [];
        
        if ($dateFrom) {
            $whereClause .= " AND oi.created_at >= ?";
            $params[] = $dateFrom . ' 00:00:00';
        }
        
        if ($dateTo) {
            $whereClause .= " AND oi.created_at <= ?";
            $params[] = $dateTo . ' 23:59:59';
        }

        $query = $db->query("
            SELECT 
                c.name as category_name,
                SUM(oi.quantity) as total_sold,
                SUM(oi.total_price) as total_revenue,
                COUNT(DISTINCT oi.order_id) as order_count
            FROM order_items oi
            LEFT JOIN products p ON oi.product_id = p.id
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE 1=1 {$whereClause}
            GROUP BY c.id, c.name
            ORDER BY total_revenue DESC
        ", $params);

        return $query->getResultArray();
    }

    /**
     * Get monthly sales data
     */
    public function getMonthlySalesData($year = null)
    {
        $year = $year ?? date('Y');
        
        $db = \Config\Database::connect();
        
        $query = $db->query("
            SELECT 
                MONTH(oi.created_at) as month,
                SUM(oi.quantity) as total_sold,
                SUM(oi.total_price) as total_revenue,
                COUNT(DISTINCT oi.order_id) as order_count
            FROM order_items oi
            WHERE YEAR(oi.created_at) = ?
            GROUP BY MONTH(oi.created_at)
            ORDER BY month
        ", [$year]);

        $results = $query->getResultArray();
        
        // Fill missing months with zeros
        $monthlyData = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthlyData[$month] = [
                'month' => $month,
                'month_name' => date('F', mktime(0, 0, 0, $month, 1)),
                'total_sold' => 0,
                'total_revenue' => 0,
                'order_count' => 0,
            ];
        }

        foreach ($results as $row) {
            $monthlyData[$row['month']] = array_merge($monthlyData[$row['month']], $row);
        }

        return array_values($monthlyData);
    }

    /**
     * Get customer purchase history
     */
    public function getCustomerPurchaseHistory($customerId, $limit = 10)
    {
        $db = \Config\Database::connect();
        
        $query = $db->query("
            SELECT 
                oi.product_id,
                oi.product_name,
                oi.product_sku,
                SUM(oi.quantity) as total_quantity,
                SUM(oi.total_price) as total_spent,
                COUNT(DISTINCT oi.order_id) as order_count,
                MAX(oi.created_at) as last_purchase
            FROM order_items oi
            LEFT JOIN orders o ON oi.order_id = o.id
            WHERE o.customer_id = ?
            GROUP BY oi.product_id, oi.product_name, oi.product_sku
            ORDER BY total_spent DESC
            LIMIT ?
        ", [$customerId, $limit]);

        return $query->getResultArray();
    }

    /**
     * Check if product is in any active orders
     */
    public function isProductInActiveOrders($productId)
    {
        $db = \Config\Database::connect();
        
        $query = $db->query("
            SELECT COUNT(*) as count
            FROM order_items oi
            LEFT JOIN orders o ON oi.order_id = o.id
            WHERE oi.product_id = ? 
            AND o.status NOT IN ('delivered', 'cancelled', 'refunded')
        ", [$productId]);

        return $query->getRow()->count > 0;
    }

    /**
     * Update order item
     */
    public function updateOrderItem($orderItemId, $data)
    {
        $orderItem = $this->find($orderItemId);
        
        if (!$orderItem) {
            return false;
        }

        // Recalculate total price if quantity or unit price changed
        if (isset($data['quantity']) || isset($data['unit_price'])) {
            $quantity = $data['quantity'] ?? $orderItem['quantity'];
            $unitPrice = $data['unit_price'] ?? $orderItem['unit_price'];
            $data['total_price'] = $quantity * $unitPrice;
        }

        return $this->update($orderItemId, $data);
    }

    /**
     * Delete order item
     */
    public function deleteOrderItem($orderItemId)
    {
        $orderItem = $this->find($orderItemId);
        
        if (!$orderItem) {
            return false;
        }

        // Restore product stock
        $productModel = new ProductModel();
        $productModel->updateStock($orderItem['product_id'], $orderItem['quantity'], 'return', $orderItem['order_id']);

        // Delete order item
        return $this->delete($orderItemId);
    }

    /**
     * Get order items summary
     */
    public function getOrderItemsSummary($orderId)
    {
        $result = $this->where('order_id', $orderId)
                      ->select('
                          SUM(quantity) as total_items,
                          SUM(total_price) as total_amount,
                          COUNT(*) as item_count
                      ')
                      ->get()
                      ->getRowArray();

        return $result ?: [
            'total_items' => 0,
            'total_amount' => 0,
            'item_count' => 0,
        ];
    }

    /**
     * Create order item from product
     */
    public function createFromProduct($productId, $quantity, $orderId = null)
    {
        $productModel = new ProductModel();
        $product = $productModel->find($productId);

        if (!$product) {
            return false;
        }

        $data = [
            'order_id' => $orderId,
            'product_id' => $product['id'],
            'quantity' => $quantity,
            'unit_price' => $product['sale_price'] ?? $product['price'],
            'total_price' => $quantity * ($product['sale_price'] ?? $product['price']),
            'product_name' => $product['name'],
            'product_sku' => $product['sku'],
            'product_image' => $product['images'] ? json_decode($product['images'])[0] : null,
        ];

        return $this->insert($data);
    }

    /**
     * Get inventory impact from orders
     */
    public function getInventoryImpact($productId, $dateFrom = null, $dateTo = null)
    {
        $builder = $this->builder()
                      ->select('
                          SUM(CASE WHEN o.status NOT IN (\'cancelled\', \'refunded\') THEN quantity ELSE 0 END) as sold_quantity,
                          SUM(CASE WHEN o.status IN (\'cancelled\', \'refunded\') THEN quantity ELSE 0 END) as returned_quantity
                      ')
                      ->join('orders o', 'o.id = order_items.order_id')
                      ->where('product_id', $productId);

        if ($dateFrom) {
            $builder->where('order_items.created_at >=', $dateFrom . ' 00:00:00');
        }

        if ($dateTo) {
            $builder->where('order_items.created_at <=', $dateTo . ' 23:59:59');
        }

        return $builder->get()->getRowArray();
    }
}
