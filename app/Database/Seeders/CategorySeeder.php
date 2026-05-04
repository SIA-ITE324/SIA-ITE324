<?php

namespace App\Database\Seeders;

use CodeIgniter\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Roses',
                'slug' => 'roses',
                'description' => 'Beautiful roses in various colors and arrangements',
                'sort_order' => 1,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Lilies',
                'slug' => 'lilies',
                'description' => 'Elegant lilies for any occasion',
                'sort_order' => 2,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Tulips',
                'slug' => 'tulips',
                'description' => 'Colorful tulips perfect for spring',
                'sort_order' => 3,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Orchids',
                'slug' => 'orchids',
                'description' => 'Exotic orchids for special occasions',
                'sort_order' => 4,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Mixed Bouquets',
                'slug' => 'mixed-bouquets',
                'description' => 'Beautiful mixed flower arrangements',
                'sort_order' => 5,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Floral Arrangements',
                'slug' => 'floral-arrangements',
                'description' => 'Professional floral arrangements for events',
                'sort_order' => 6,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('categories')->insertBatch($categories);
    }
}
