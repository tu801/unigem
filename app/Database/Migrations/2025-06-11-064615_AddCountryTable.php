<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCountryTable extends Migration
{
    public function up()
    {
        /*
         * Create table country
         */
        $this->forge->addField([
            'id'                    => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'code'                  => ['type' => 'varchar', 'constraint' => 32, 'null' => true],
            'name'                  => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'full_name'             => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'flags'                 => ['type' => 'text', 'null' => true],
            'currency'              => ['type' => 'text', 'null' => true],
            'created_at'            => ['type' => 'datetime', 'null' => true],
            'updated_at'            => ['type' => 'datetime', 'null' => true],
            'deleted_at'            => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('code');
        $this->forge->addKey('name');
        $this->forge->createTable('countries', true);

        // add field country_id to customer table
        $db = \Config\Database::connect();
        $tblPrefix = $db->getPrefix();
        $customerFields  = [
            'country_id' => [
                'type' => 'int',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'cus_birthday'
            ],
        ];
        $this->forge->addColumn('customer', $customerFields);
        $db->query("ALTER TABLE `{$tblPrefix}customer` ADD INDEX `idx_customer_country_id` (`country_id`)");

        $shipAddressFields   = [
            'country_id' => [
                'type' => 'int',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'cus_id'
            ],
        ];
        $this->forge->addColumn('customer_ship_address', $shipAddressFields);
        $db->query("ALTER TABLE `{$tblPrefix}customer_ship_address` ADD INDEX `idx_ship_address_country_id` (`country_id`)");
    }

    public function down()
    {
        // delete table country
        $this->forge->dropTable('countries');

        // drop field country_id from customer table
        $this->forge->dropColumn('customer', 'country_id');
        $this->forge->dropColumn('customer_ship_address', 'country_id');
    }
}