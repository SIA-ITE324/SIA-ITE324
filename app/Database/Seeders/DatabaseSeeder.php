<?php

namespace App\Database\Seeders;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Seed categories first
        $this->call('CategorySeeder');
        
        // Then seed products
        $this->call('ProductSeeder');
    }
}
