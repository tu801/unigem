<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CustomerData extends Migration
{
    public function up()
    {
        //Table Customer
        $this->forge->addField([
            'id'              	=> ['type' => 'bigint', 'unsigned' => true, 'auto_increment' => true],
            'user_id'         	=> ['type' => 'bigint', 'unsigned' => true, 'null' => true],
            'cus_code'     		=> ['type' => 'varchar', 'constraint' => 32],
            'cus_full_name'     => ['type' => 'varchar', 'constraint' => 255],
            'cus_email'     	=> ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'cus_phone'     	=> ['type' => 'varchar', 'constraint' => 20],
            'cus_address'     	=> ['type' => 'varchar', 'constraint' => 500, 'null' => true],
            'cus_birthday'      => ['type' => 'date', 'null' => true],
            'province_id'       => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'district_id'       => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'ward_id'         	=> ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'active'           	=> ['type' => 'tinyint', 'constraint' => 1, 'null' => true, 'default' => 0, 'comment' => 'active = 1 | inactive = 0'],
            'created_at'       	=> ['type' => 'datetime', 'null' => true],
            'updated_at'       	=> ['type' => 'datetime', 'null' => true],
            'deleted_at'       	=> ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('province_id');
        $this->forge->addKey('district_id');
        $this->forge->addKey('ward_id');
        $this->forge->addUniqueKey('cus_code');
        $this->forge->addUniqueKey('cus_phone');
        $this->forge->addKey('user_id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE', 'idx_users_id');
        $this->forge->createTable('customer', true);

        //Table Customer Ship Address
        $fields = [
            'id'          		=> ['type' => 'bigint', 'unsigned' => true, 'auto_increment' => true],
            'cus_id'        	=> ['type' => 'bigint', 'unsigned' => true],
            'province_id'       => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'district_id'       => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'ward_id'        	=> ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'ship_full_name'	=> ['type' => 'varchar', 'constraint' => 50],
            'ship_telephone'	=> ['type' => 'varchar', 'constraint' => 20],
            'ship_address'      => ['type' => 'varchar', 'constraint' => 500],
            'ship_email'        => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
        ];
        $this->forge->addField($fields);
        $this->forge->addKey('id', true);
        $this->forge->addKey('cus_id');
        $this->forge->addKey('province_id');
        $this->forge->addKey('district_id');
        $this->forge->addKey('ward_id');
        $this->forge->addForeignKey('cus_id', 'customer', 'id', 'CASCADE', 'CASCADE', 'idx_customer_id');
        $this->forge->createTable('customer_ship_address', true);
    }

    public function down()
    {
        // drop foreign key
        $this->forge->dropForeignKey('customer','idx_users_id');
        $this->forge->dropForeignKey('customer_ship_address','idx_customer_id');

        //drop table
        $this->forge->dropTable('customer', true);
        $this->forge->dropTable('customer_ship_address', true);
    }
}
