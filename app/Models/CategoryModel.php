<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'name',
        'slug',
        'description',
        'image',
        'parent_id',
        'sort_order',
        'status'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'datetime';

    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[100]',
        'slug' => 'required|alpha_dash|is_unique[categories.slug,id,{id}]',
        'status' => 'required|in_list[active,inactive]',
        'parent_id' => 'permit_empty|integer|is_not_unique[categories.id]',
        'sort_order' => 'required|integer|greater_than_equal[0]',
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Category name is required.',
            'min_length' => 'Category name must be at least 2 characters long.',
        ],
        'slug' => [
            'required' => 'Category slug is required.',
            'is_unique' => 'Category slug must be unique.',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Get active categories
     */
    public function getActiveCategories()
    {
        return $this->where('status', 'active')
                   ->orderBy('sort_order', 'ASC')
                   ->orderBy('name', 'ASC')
                   ->findAll();
    }

    /**
     * Get category by slug
     */
    public function getCategoryBySlug($slug)
    {
        return $this->where('slug', $slug)
                   ->where('status', 'active')
                   ->first();
    }

    /**
     * Get category with product count
     */
    public function getCategoryWithProductCount($categoryId)
    {
        $db = \Config\Database::connect();
        
        $query = $db->query("
            SELECT c.*, 
                   COUNT(p.id) as product_count,
                   COUNT(CASE WHEN p.status = 'active' THEN 1 END) as active_product_count
            FROM categories c
            LEFT JOIN products p ON c.id = p.category_id
            WHERE c.id = ?
            GROUP BY c.id
        ", [$categoryId]);

        return $query->getRowArray();
    }

    /**
     * Get all categories with product counts
     */
    public function getCategoriesWithProductCounts()
    {
        $db = \Config\Database::connect();
        
        $query = $db->query("
            SELECT c.*, 
                   COUNT(p.id) as product_count,
                   COUNT(CASE WHEN p.status = 'active' THEN 1 END) as active_product_count
            FROM categories c
            LEFT JOIN products p ON c.id = p.category_id
            GROUP BY c.id
            ORDER BY c.sort_order ASC, c.name ASC
        ");

        return $query->getResultArray();
    }

    /**
     * Get parent categories
     */
    public function getParentCategories($excludeId = null)
    {
        $builder = $this->where('parent_id', null)
                       ->where('status', 'active');

        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }

        return $builder->orderBy('sort_order', 'ASC')
                      ->orderBy('name', 'ASC')
                      ->findAll();
    }

    /**
     * Get child categories
     */
    public function getChildCategories($parentId)
    {
        return $this->where('parent_id', $parentId)
                   ->where('status', 'active')
                   ->orderBy('sort_order', 'ASC')
                   ->orderBy('name', 'ASC')
                   ->findAll();
    }

    /**
     * Get category tree
     */
    public function getCategoryTree()
    {
        $categories = $this->getActiveCategories();
        $tree = [];

        foreach ($categories as $category) {
            if ($category['parent_id'] === null) {
                $tree[] = $this->buildCategoryNode($category, $categories);
            }
        }

        return $tree;
    }

    /**
     * Build category node for tree
     */
    private function buildCategoryNode($category, $allCategories)
    {
        $node = $category;
        $node['children'] = [];

        foreach ($allCategories as $child) {
            if ($child['parent_id'] == $category['id']) {
                $node['children'][] = $this->buildCategoryNode($child, $allCategories);
            }
        }

        return $node;
    }

    /**
     * Get category path
     */
    public function getCategoryPath($categoryId)
    {
        $path = [];
        $category = $this->find($categoryId);

        while ($category) {
            array_unshift($path, $category);
            if ($category['parent_id']) {
                $category = $this->find($category['parent_id']);
            } else {
                break;
            }
        }

        return $path;
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
     * Update sort order
     */
    public function updateSortOrder($categoryIds)
    {
        foreach ($categoryIds as $index => $categoryId) {
            $this->update($categoryId, ['sort_order' => $index + 1]);
        }

        return true;
    }

    /**
     * Check if category has products
     */
    public function hasProducts($categoryId)
    {
        $db = \Config\Database::connect();
        $count = $db->table('products')
                    ->where('category_id', $categoryId)
                    ->countAllResults();

        return $count > 0;
    }

    /**
     * Check if category has children
     */
    public function hasChildren($categoryId)
    {
        return $this->where('parent_id', $categoryId)->countAllResults() > 0;
    }

    /**
     * Get category statistics
     */
    public function getCategoryStats()
    {
        $db = \Config\Database::connect();
        
        $query = $db->query("
            SELECT 
                COUNT(*) as total_categories,
                COUNT(CASE WHEN status = 'active' THEN 1 END) as active_categories,
                COUNT(CASE WHEN status = 'inactive' THEN 1 END) as inactive_categories,
                COUNT(CASE WHEN parent_id IS NULL THEN 1 END) as parent_categories,
                COUNT(CASE WHEN parent_id IS NOT NULL THEN 1 END) as child_categories
            FROM categories
        ");

        return $query->getRowArray();
    }

    /**
     * Search categories
     */
    public function searchCategories($searchTerm, $status = null)
    {
        $builder = $this->builder();

        if ($searchTerm) {
            $builder->groupStart()
                   ->like('name', $searchTerm)
                   ->orLike('description', $searchTerm)
                   ->groupEnd();
        }

        if ($status) {
            $builder->where('status', $status);
        }

        return $builder->orderBy('sort_order', 'ASC')
                      ->orderBy('name', 'ASC')
                      ->findAll();
    }

    /**
     * Delete category with checks
     */
    public function deleteCategory($categoryId)
    {
        // Check if category has products
        if ($this->hasProducts($categoryId)) {
            return false;
        }

        // Check if category has children
        if ($this->hasChildren($categoryId)) {
            return false;
        }

        return $this->delete($categoryId);
    }

    /**
     * Get categories for dropdown
     */
    public function getCategoriesForDropdown($excludeId = null)
    {
        $builder = $this->where('status', 'active');

        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }

        $categories = $builder->orderBy('sort_order', 'ASC')
                             ->orderBy('name', 'ASC')
                             ->findAll();

        $options = [];
        foreach ($categories as $category) {
            $prefix = $category['parent_id'] ? '— ' : '';
            $options[$category['id']] = $prefix . $category['name'];
        }

        return $options;
    }

    /**
     * Bulk import categories
     */
    public function bulkImport($data)
    {
        $success = 0;
        $errors = [];

        foreach ($data as $index => $row) {
            try {
                // Generate slug if not provided
                if (empty($row['slug'])) {
                    $row['slug'] = $this->createSlug($row['name']);
                }

                // Set default values
                $row['status'] = $row['status'] ?? 'active';
                $row['sort_order'] = $row['sort_order'] ?? 0;

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
     * Export categories
     */
    public function exportCategories()
    {
        $categories = $this->orderBy('sort_order', 'ASC')
                           ->orderBy('name', 'ASC')
                           ->findAll();

        $exportData = [];
        foreach ($categories as $category) {
            $exportData[] = [
                'ID' => $category['id'],
                'Name' => $category['name'],
                'Slug' => $category['slug'],
                'Description' => $category['description'],
                'Parent ID' => $category['parent_id'],
                'Sort Order' => $category['sort_order'],
                'Status' => $category['status'],
                'Created At' => $category['created_at'],
            ];
        }

        return $exportData;
    }
}
