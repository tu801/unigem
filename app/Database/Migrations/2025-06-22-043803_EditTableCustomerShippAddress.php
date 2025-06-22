<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EditTableCustomerShippAddress extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $tblPrefix = $db->getPrefix();

        // add new field to customer_shipping_address table
        $fields = [
            'is_default' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'ship_address',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            ],
        ];
        $this->forge->addColumn('customer_ship_address', $fields);
        // add email index to customer table
        $db->query("ALTER TABLE `{$tblPrefix}customer_ship_address` ADD INDEX `idx_shipping_address_default` (`is_default`)");
        $db->query("ALTER TABLE `{$tblPrefix}customer_ship_address` ADD INDEX `idx_shipping_address_telephone` (`ship_telephone`)");
    }

    public function down()
    {
        // remove the fields added in the up method
        $this->forge->dropColumn('customer_ship_address', 'is_default');
        $this->forge->dropColumn('customer_ship_address', 'created_at');    
        $this->forge->dropColumn('customer_ship_address', 'updated_at');
    }
}
