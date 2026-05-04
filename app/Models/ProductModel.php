<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\I18n\Time;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'category_id',
        'name',
        'slug',
        'description',
        'short_description',
        'sku',
        'price',
        'sale_price',
        'cost_price',
        'stock_quantity',
        'min_stock_level',
        'weight',
        'dimensions_length',
        'dimensions_width',
        'dimensions_height',
        'images',
        'tags',
        'meta_title',
        'meta_description',
        'status',
        'is_featured',
        'is_digital',
        'track_stock'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'datetime';

    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[200]',
        'slug' => 'required|alpha_dash|is_unique[products.slug,id,{id}]',
        'sku' => 'required|alpha_numeric|is_unique[products.sku,id,{id}]',
        'price' => 'required|numeric|greater_than[0]',
        'sale_price' => 'permit_empty|numeric|greater_than[0]',
        'cost_price' => 'permit_empty|numeric|greater_than[0]',
        'stock_quantity' => 'required|integer|greater_than_equal[0]',
        'min_stock_level' => 'required|integer|greater_than[0]',
        'status' => 'required|in_list[active,inactive,out_of_stock]',
        'category_id' => 'permit_empty|integer|is_not_unique[categories.id]',
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Product name is required.',
            'min_length' => 'Product name must be at least 2 characters long.',
        ],
        'slug' => [
            'required' => 'Product slug is required.',
            'is_unique' => 'Product slug must be unique.',
        ],
        'sku' => [
            'required' => 'Product SKU is required.',
            'is_unique' => 'Product SKU must be unique.',
        ],
        'price' => [
            'required' => 'Product price is required.',
            'numeric' => 'Product price must be a number.',
            'greater_than' => 'Product price must be greater than 0.',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Get featured products
     */
    public function getFeaturedProducts($limit = 8)
    {
        return $this->where('is_featured', true)
                   ->where('status', 'active')
                   ->orderBy('created_at', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }

    /**
     * Get latest products
     */
    public function getLatestProducts($limit = 4)
    {
        return $this->where('status', 'active')
                   ->orderBy('created_at', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }

    /**
     * Get product by slug
     */
    public function getProductBySlug($slug)
    {
        return $this->where('slug', $slug)
                   ->where('status', 'active')
                   ->first();
    }

    /**
     * Get related products
     */
    public function getRelatedProducts($categoryId, $excludeId, $limit = 4)
    {
        return $this->where('category_id', $categoryId)
                   ->where('id !=', $excludeId)
                   ->where('status', 'active')
                   ->orderBy('created_at', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }

    /**
     * Get filtered products
     */
    public function getFilteredProducts($category = null, $search = null, $sort = 'name', $order = 'asc', $page = 1, $perPage = 12)
    {
        $builder = $this->builder();

        // Filter by category
        if ($category) {
            $builder->where('category_id', $category);
        }

        // Search
        if ($search) {
            $builder->groupStart()
                   ->like('name', $search)
                   ->orLike('description', $search)
                   ->orLike('short_description', $search)
                   ->orLike('sku', $search)
                   ->groupEnd();
        }

        // Filter active products
        $builder->where('status', 'active');

        // Sort
        $allowedSorts = ['name', 'price', 'created_at', 'stock_quantity'];
        $allowedOrders = ['asc', 'desc'];
        
        $sort = in_array($sort, $allowedSorts) ? $sort : 'name';
        $order = in_array($order, $allowedOrders) ? $order : 'asc';
        
        $builder->orderBy($sort, $order);

        // Get total count
        $total = $builder->countAllResults(false);

        // Get paginated results
        $offset = ($page - 1) * $perPage;
        $products = $builder->limit($perPage, $offset)->get()->getResultArray();

        // Create pagination
        $pagination = [
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'total_pages' => ceil($total / $perPage),
            'has_next' => $page < ceil($total / $perPage),
            'has_prev' => $page > 1,
        ];

        return [
            'data' => $products,
            'pagination' => $pagination,
        ];
    }

    /**
     * Search products
     */
    public function searchProducts($search, $limit = 10)
    {
        return $this->groupStart()
                   ->like('name', $search)
                   ->orLike('description', $search)
                   ->orLike('short_description', $search)
                   ->orLike('sku', $search)
                   ->groupEnd()
                   ->where('status', 'active')
                   ->orderBy('name', 'ASC')
                   ->limit($limit)
                   ->findAll();
    }

    /**
     * Get product count by category
     */
    public function getProductCountByCategory($categoryId)
    {
        return $this->where('category_id', $categoryId)
                   ->where('status', 'active')
                   ->countAllResults();
    }

    /**
     * Get low stock products
     */
    public function getLowStockProducts()
    {
        return $this->where('stock_quantity <= min_stock_level')
                   ->where('status', 'active')
                   ->where('track_stock', true)
                   ->orderBy('stock_quantity', 'ASC')
                   ->findAll();
    }

    /**
     * Update stock quantity
     */
    public function updateStock($productId, $quantity, $type = 'sale', $referenceId = null)
    {
        $product = $this->find($productId);
        
        if (!$product) {
            return false;
        }

        $oldQuantity = $product['stock_quantity'];
        $newQuantity = $oldQuantity;
        $quantityChange = 0;

        switch ($type) {
            case 'sale':
                $newQuantity = max(0, $oldQuantity - $quantity);
                $quantityChange = -$quantity;
                break;
            case 'purchase':
                $newQuantity = $oldQuantity + $quantity;
                $quantityChange = $quantity;
                break;
            case 'adjustment':
                $newQuantity = $quantity;
                $quantityChange = $quantity - $oldQuantity;
                break;
            case 'return':
                $newQuantity = $oldQuantity + $quantity;
                $quantityChange = $quantity;
                break;
        }

        // Update product
        $this->update($productId, ['stock_quantity' => $newQuantity]);

        // Log inventory change
        $db = \Config\Database::connect();
        $inventoryData = [
            'product_id' => $productId,
            'quantity_before' => $oldQuantity,
            'quantity_after' => $newQuantity,
            'quantity_change' => $quantityChange,
            'type' => $type,
            'reference_id' => $referenceId,
            'reference_type' => $this->getReferenceType($type),
            'notes' => "Stock updated via {$type}",
            'created_by' => session()->get('user_id'),
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $db->table('inventory')->insert($inventoryData);

        return true;
    }

    /**
     * Get reference type for inventory
     */
    private function getReferenceType($type)
    {
        $referenceTypes = [
            'sale' => 'order',
            'purchase' => 'purchase',
            'adjustment' => 'adjustment',
            'return' => 'order',
            'damage' => 'adjustment',
            'transfer' => 'transfer',
        ];

        return $referenceTypes[$type] ?? 'adjustment';
    }

    /**
     * Get products with inventory stats
     */
    public function getProductsWithInventoryStats()
    {
        return $this->select('products.*, categories.name as category_name')
                   ->join('categories', 'categories.id = products.category_id', 'left')
                   ->where('products.status', 'active')
                   ->orderBy('products.name', 'ASC')
                   ->findAll();
    }

    /**
     * Generate unique SKU
     */
    public function generateUniqueSKU($prefix = 'PROD')
    {
        $timestamp = date('Ymd');
        $random = mt_rand(100, 999);
        $sku = $prefix . $timestamp . $random;

        // Check if SKU already exists
        while ($this->where('sku', $sku)->first()) {
            $random = mt_rand(100, 999);
            $sku = $prefix . $timestamp . $random;
        }

        return $sku;
    }

    /**
     * Create slug from name
     */
    public function createSlug($name, $id = null)
    {
        $slug = url_title($name, '-', true);
        $originalSlug = $slug;
        $counter = 1;

        // Check if slug already exists
        while ($this->where('slug', $slug)->where('id !=', $id)->first()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Get product statistics
     */
    public function getProductStats()
    {
        $db = \Config\Database::connect();
        
        $query = $db->query("
            SELECT 
                COUNT(*) as total_products,
                COUNT(CASE WHEN status = 'active' THEN 1 END) as active_products,
                COUNT(CASE WHEN status = 'inactive' THEN 1 END) as inactive_products,
                COUNT(CASE WHEN status = 'out_of_stock' THEN 1 END) as out_of_stock_products,
                COUNT(CASE WHEN is_featured = 1 THEN 1 END) as featured_products,
                COUNT(CASE WHEN stock_quantity <= min_stock_level THEN 1 END) as low_stock_products,
                SUM(stock_quantity) as total_stock,
                AVG(price) as avg_price,
                MIN(price) as min_price,
                MAX(price) as max_price
            FROM products
        ");

        return $query->getRowArray();
    }

    /**
     * Bulk import products
     */
    public function bulkImport($data)
    {
        $success = 0;
        $errors = [];

        foreach ($data as $index => $row) {
            try {
                // Generate SKU if not provided
                if (empty($row['sku'])) {
                    $row['sku'] = $this->generateUniqueSKU();
                }

                // Generate slug if not provided
                if (empty($row['slug'])) {
                    $row['slug'] = $this->createSlug($row['name']);
                }

                // Set default values
                $row['status'] = $row['status'] ?? 'active';
                $row['stock_quantity'] = $row['stock_quantity'] ?? 0;
                $row['min_stock_level'] = $row['min_stock_level'] ?? 5;
                $row['track_stock'] = $row['track_stock'] ?? true;
                $row['is_featured'] = $row['is_featured'] ?? false;
                $row['is_digital'] = $row['is_digital'] ?? false;

                if ($this->insert($row)) {
                    $success++;
                } else {
                    $errors[] = "Row " . ($index + 2) . ": " . implode(', ', $this->errors());
                }
            } catch (\Exception $e) {
                $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
            }
        }

        return [
            'success' => $success,
            'errors' => $errors,
        ];
    }

    /**
     * Export products
     */
    public function exportProducts($category = null)
    {
        $builder = $this->builder();

        if ($category) {
            $builder->where('category_id', $category);
        }

        $products = $builder->get()->getResultArray();

        $exportData = [];
        foreach ($products as $product) {
            $exportData[] = [
                'ID' => $product['id'],
                'Name' => $product['name'],
                'SKU' => $product['sku'],
                'Category' => $this->getCategoryName($product['category_id']),
                'Price' => $product['price'],
                'Sale Price' => $product['sale_price'],
                'Stock Quantity' => $product['stock_quantity'],
                'Min Stock Level' => $product['min_stock_level'],
                'Status' => $product['status'],
                'Featured' => $product['is_featured'] ? 'Yes' : 'No',
                'Created At' => $product['created_at'],
            ];
        }

        return $exportData;
    }

    /**
     * Get category name by ID
     */
    private function getCategoryName($categoryId)
    {
        if (!$categoryId) {
            return 'No Category';
        }

        $db = \Config\Database::connect();
        $category = $db->table('categories')->where('id', $categoryId)->get()->getRow();
        
        return $category ? $category->name : 'Unknown';
    }
}
