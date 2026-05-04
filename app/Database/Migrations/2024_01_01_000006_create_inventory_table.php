<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInventoryTable extends Migration
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
            'product_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'null' => false,
            ],
            'quantity_before' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => false,
            ],
            'quantity_after' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => false,
            ],
            'quantity_change' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => false,
            ],
            'type' => [
                'type' => 'ENUM',
                'constraint' => ['sale', 'purchase', 'adjustment', 'return', 'damage', 'transfer'],
                'null' => false,
            ],
            'reference_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'null' => true,
                'comment' => 'Reference to order_id, purchase_id, etc.',
            ],
            'reference_type' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
                'comment' => 'order, purchase, adjustment, etc.',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'null' => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addKey('product_id');
        $this->forge->addKey('type');
        $this->forge->addKey('created_at');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('inventory');
    }

    public function down()
    {
        $this->forge->dropTable('inventory');
    }
}
