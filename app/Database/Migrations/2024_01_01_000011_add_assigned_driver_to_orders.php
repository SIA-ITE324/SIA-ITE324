<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAssignedDriverToOrders extends Migration
{
    public function up()
    {
        $this->forge->addColumn('orders', [
            'assigned_driver' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'actual_delivery'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('orders', 'assigned_driver');
    }
}
