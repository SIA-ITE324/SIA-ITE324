<?php

class SimpleHome extends SimpleBaseController {
    
    public function index() {
        // Get some sample data for the homepage
        $featuredProducts = [];
        $result = $this->db->query("SELECT * FROM products WHERE is_featured = 1 AND status = 'active' LIMIT 4");
        
        while ($row = $result->fetch_assoc()) {
            $featuredProducts[] = $row;
        }
        
        $data = [
            'featured_products' => $featuredProducts,
            'page_title' => 'Welcome to Fleur - Flower Order Management System',
            'meta_description' => 'Beautiful flowers and arrangements for every occasion. Order online with fast delivery.',
        ];
        
        return $this->view('home/index', $data);
    }
    
    public function about() {
        $data = [
            'page_title' => 'About Fleur',
            'meta_description' => 'Learn about Fleur - your trusted flower delivery service.',
        ];
        
        return $this->view('home/about', $data);
    }
    
    public function contact() {
        $data = [
            'page_title' => 'Contact Fleur',
            'meta_description' => 'Get in touch with Fleur for all your flower needs.',
        ];
        
        return $this->view('home/contact', $data);
    }
}
?>
