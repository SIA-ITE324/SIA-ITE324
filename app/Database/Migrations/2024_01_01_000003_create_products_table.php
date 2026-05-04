<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'category_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'null' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '200',
                'null' => false,
            ],
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => '200',
                'unique' => true,
                'null' => false,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'short_description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'sku' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'unique' => true,
                'null' => false,
            ],
            'price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
            ],
            'sale_price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'cost_price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'stock_quantity' => [
                'type' => 'INT',
                'constraint' => 5,
                'default' => 0,
                'null' => false,
            ],
            'min_stock_level' => [
                'type' => 'INT',
                'constraint' => 5,
                'default' => 5,
                'null' => false,
            ],
            'weight' => [
                'type' => 'DECIMAL',
                'constraint' => '8,2',
                'null' => true,
            ],
            'dimensions_length' => [
                'type' => 'DECIMAL',
                'constraint' => '8,2',
                'null' => true,
            ],
            'dimensions_width' => [
                'type' => 'DECIMAL',
                'constraint' => '8,2',
                'null' => true,
            ],
            'dimensions_height' => [
                'type' => 'DECIMAL',
                'constraint' => '8,2',
                'null' => true,
            ],
            'images' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'JSON array of image URLs',
            ],
            'tags' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'JSON array of tags',
            ],
            'meta_title' => [
                'type' => 'VARCHAR',
                'constraint' => '200',
                'null' => true,
            ],
            'meta_description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'inactive', 'out_of_stock'],
                'default' => 'active',
                'null' => false,
            ],
            'is_featured' => [
                'type' => 'BOOLEAN',
                'default' => false,
                'null' => false,
            ],
            'is_digital' => [
                'type' => 'BOOLEAN',
                'default' => false,
                'null' => false,
            ],
            'track_stock' => [
                'type' => 'BOOLEAN',
                'default' => true,
                'null' => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addKey('category_id');
        $this->forge->addKey('sku');
        $this->forge->addKey('status');
        $this->forge->addForeignKey('category_id', 'categories', 'id', 'SET NULL', 'SET NULL');
        $this->forge->createTable('products');
    }

    public function down()
    {
        $this->forge->dropTable('products');
    }
}
