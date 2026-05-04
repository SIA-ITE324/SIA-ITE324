<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\CategoryModel;

class Home extends BaseController
{
    protected $productModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
    }

    /**
     * Display homepage
     */
    public function index()
    {
        // Get featured products
        $featuredProducts = $this->productModel->getFeaturedProducts(8);
        
        // Get categories
        $categories = $this->categoryModel->getActiveCategories();
        
        // Get latest products
        $latestProducts = $this->productModel->getLatestProducts(4);
        
        $data = [
            'featured_products' => $featuredProducts,
            'categories' => $categories,
            'latest_products' => $latestProducts,
            'page_title' => 'Welcome to Fleur - Flower Order Management System',
            'meta_description' => 'Beautiful flowers and arrangements for every occasion. Order online with fast delivery.',
        ];

        return view('home/index', $data);
    }

    /**
     * Display about page
     */
    public function about()
    {
        $data = [
            'page_title' => 'About Fleur',
            'meta_description' => 'Learn about Fleur - your trusted flower delivery service.',
        ];

        return view('home/about', $data);
    }

    /**
     * Display contact page
     */
    public function contact()
    {
        $data = [
            'page_title' => 'Contact Fleur',
            'meta_description' => 'Get in touch with Fleur for all your flower needs.',
        ];

        return view('home/contact', $data);
    }

    /**
     * Process contact form
     */
    public function sendContact()
    {
        $rules = [
            'name' => 'required|min_length[2]|max_length[100]',
            'email' => 'required|valid_email',
            'subject' => 'required|min_length[5]|max_length[200]',
            'message' => 'required|min_length[10]|max_length[1000]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $name = $this->request->getPost('name');
        $email = $this->request->getPost('email');
        $subject = $this->request->getPost('subject');
        $message = $this->request->getPost('message');

        // TODO: Send email to admin
        $emailContent = "Name: {$name}\nEmail: {$email}\n\nMessage:\n{$message}";
        
        $this->sendEmail('admin@fleur.com', 'Contact Form: ' . $subject, $emailContent);

        return redirect()->to('/contact')->with('success', 'Thank you for your message. We will get back to you soon!');
    }

    /**
     * Display products page
     */
    public function products()
    {
        $category = $this->request->getGet('category');
        $search = $this->request->getGet('search');
        $sort = $this->request->getGet('sort', 'name');
        $order = $this->request->getGet('order', 'asc');
        $page = $this->request->getGet('page', 1);

        $products = $this->productModel->getFilteredProducts($category, $search, $sort, $order, $page, 12);
        $categories = $this->categoryModel->getActiveCategories();

        $data = [
            'products' => $products['data'],
            'pagination' => $products['pagination'],
            'categories' => $categories,
            'selected_category' => $category,
            'search_term' => $search,
            'sort' => $sort,
            'order' => $order,
            'page_title' => 'Our Products - Fleur',
            'meta_description' => 'Browse our beautiful collection of flowers and arrangements.',
        ];

        return view('home/products', $data);
    }

    /**
     * Display product details
     */
    public function productDetail($slug)
    {
        $product = $this->productModel->getProductBySlug($slug);

        if (!$product) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Product not found');
        }

        // Get related products
        $relatedProducts = $this->productModel->getRelatedProducts($product['category_id'], $product['id'], 4);

        $data = [
            'product' => $product,
            'related_products' => $relatedProducts,
            'page_title' => $product['name'] . ' - Fleur',
            'meta_description' => $product['meta_description'] ?? $product['short_description'] ?? $product['description'],
        ];

        return view('home/product_detail', $data);
    }

    /**
     * Display FAQ page
     */
    public function faq()
    {
        $data = [
            'page_title' => 'FAQ - Fleur',
            'meta_description' => 'Frequently asked questions about Fleur flower delivery service.',
        ];

        return view('home/faq', $data);
    }

    /**
     * Display terms and conditions
     */
    public function terms()
    {
        $data = [
            'page_title' => 'Terms and Conditions - Fleur',
            'meta_description' => 'Terms and conditions for using Fleur flower delivery service.',
        ];

        return view('home/terms', $data);
    }

    /**
     * Display privacy policy
     */
    public function privacy()
    {
        $data = [
            'page_title' => 'Privacy Policy - Fleur',
            'meta_description' => 'Privacy policy for Fleur flower delivery service.',
        ];

        return view('home/privacy', $data);
    }

    /**
     * Display delivery information
     */
    public function delivery()
    {
        $data = [
            'page_title' => 'Delivery Information - Fleur',
            'meta_description' => 'Delivery information and shipping options for Fleur flower delivery.',
        ];

        return view('home/delivery', $data);
    }

    /**
     * Search products (AJAX endpoint)
     */
    public function searchProducts()
    {
        $search = $this->request->getGet('q');
        $limit = $this->request->getGet('limit', 10);

        if (!$search) {
            return $this->response->setJSON([]);
        }

        $products = $this->productModel->searchProducts($search, $limit);

        $results = [];
        foreach ($products as $product) {
            $results[] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'slug' => $product['slug'],
                'price' => $product['price'],
                'image' => $product['images'] ? json_decode($product['images'])[0] : null,
            ];
        }

        return $this->response->setJSON($results);
    }

    /**
     * Get product categories (AJAX endpoint)
     */
    public function getCategories()
    {
        $categories = $this->categoryModel->getActiveCategories();

        $results = [];
        foreach ($categories as $category) {
            $results[] = [
                'id' => $category['id'],
                'name' => $category['name'],
                'slug' => $category['slug'],
                'product_count' => $this->productModel->getProductCountByCategory($category['id']),
            ];
        }

        return $this->response->setJSON($results);
    }
}
