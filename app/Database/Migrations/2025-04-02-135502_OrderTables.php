<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class OrderTables extends Migration
{
    public function up()
    {
        /**
         * Add new table order
         */
        $this->forge->addField([
            'order_id'          => ['type' => 'bigint', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_init'	      	=> ['type' => 'int', 'unsigned' => true, 'null' => true],
            'customer_id'       => ['type' => 'int', 'unsigned' => true, 'null' => true],
            'shop_id'           => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'code'	            => ['type' => 'varchar', 'constraint' => 32],
            'title'             => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'note'	            => ['type' => 'text', 'null' => true],
            'delivery_type'     => ['type' => 'tinyint', 'constraint' => 1, 'default' => 0, 'comment' => 'shipping_config to home = 1 | pickup in store = 0'],
            'delivery_info'  	=> ['type' => 'json', 'null' => true],
            'customer_info'  	=> ['type' => 'json', 'null' => true],
            'status'           	=> ['type' => 'tinyint', 'constraint' => 1, 'default' => 0],
            'payment_status'    => ['type' => 'tinyint', 'constraint' => 1, 'default' => 0],
            'payment_method'    => ['type' => 'tinyint', 'constraint' => 4, 'default' => 0],
            'currency'          => ['type' => 'varchar', 'constraint' => 32 ],
            'exchange_rate'     => ['type' => 'decimal', 'constraint' => "14,2", 'default' => 0],
            'exchange_rate_id'  => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'sub_total'         => ['type' => 'decimal', 'constraint' => "14,2", 'null' => true, 'default' => 0],
            'voucher_code'      => ['type' => 'varchar', 'constraint' => 32, 'null' => true],
            'discount_amount'   => ['type' => 'decimal', 'constraint' => "14,2", 'null' => true, 'default' => 0],
            'shipping_amount'   => ['type' => 'decimal', 'constraint' => "14,2", 'null' => true, 'default' => 0],
            'total'             => ['type' => 'decimal', 'constraint' => "14,2", 'null' => true, 'default' => 0],
            'total_amount_vnd'  => ['type' => 'decimal', 'constraint' => "14,2", 'null' => true, 'default' => 0],
            'customer_paid'     => ['type' => 'decimal', 'constraint' => "14,2", 'null' => true, 'default' => 0],
            'created_at'       	=> ['type' => 'datetime', 'null' => true],
            'updated_at'       	=> ['type' => 'datetime', 'null' => true],
            'deleted_at'       	=> ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addKey('order_id', true);
        $this->forge->addKey('user_init');
        $this->forge->addKey('customer_id');
        $this->forge->addKey('shop_id');
        $this->forge->addUniqueKey('code');
        $this->forge->addKey('status');
        $this->forge->addForeignKey('user_init', 'users', 'id', 'CASCADE', 'CASCADE', 'idx_order_user_init');
        $this->forge->addForeignKey('customer_id', 'customer', 'id', 'CASCADE', 'CASCADE', 'idx_order_customer_id');
        $this->forge->addForeignKey('shop_id', 'shop', 'shop_id', 'CASCADE', 'CASCADE', 'idx_order_shop_id');
        $this->forge->createTable('order', true);

        /**
         * Add new table order items
         */
        $this->forge->addField([
            'oder_item_id'      => ['type' => 'bigint', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'order_id'          => ['type' => 'bigint', 'constraint' => 11, 'unsigned' => true],
            'product_id'        => ['type' => 'bigint', 'constraint' => 11, 'unsigned' => true],
            'unit_price'        => ['type' => 'decimal', 'constraint' => "14,2", 'unsigned' => true, 'default' => 0],
            'quantity'          => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'default' => 0],
            'total'             => ['type' => 'decimal', 'constraint' => "14,2", 'unsigned' => true, 'default' => 0],
            'pd_type'	        => ['type' => 'varchar', 'constraint' => 32],
            'note'	            => ['type' => 'text', 'null' => true],
            'reward'            => ['type' => 'int', 'constraint' => 11, 'default' => 0],
        ]);
        $this->forge->addKey('oder_item_id', true);
        $this->forge->addKey('order_id');
        $this->forge->addKey('product_id');
        $this->forge->addForeignKey('order_id', 'order', 'order_id', 'CASCADE', 'CASCADE', 'idx_order_items_order_id');
        $this->forge->addForeignKey('product_id', 'product', 'id', 'CASCADE', 'CASCADE', 'idx_order_items_product_id');
        $this->forge->createTable('order_items', true);

        //add table currency exchange rate
        $this->forge->addField([
            'id'              	    => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'currency_from'         => ['type' => 'varchar', 'constraint' => 32],
            'currency_to'           => ['type' => 'varchar', 'constraint' => 32],
            'rate'                  => ['type' => 'decimal', 'constraint' => "14,2"],
            'is_active'      	    => ['type' => 'tinyint', 'unsigned' => true, 'default' => 0],
            'created_by'	      	=> ['type' => 'bigint', 'unsigned' => true],
            'created_at'       	    => ['type' => 'datetime', 'null' => true],
            'updated_at'       	    => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('exchange_rates', true);
    }

    public function down()
    {
        // drop foreign key
        $this->forge->dropForeignKey('order','idx_order_user_init');
        $this->forge->dropForeignKey('order','idx_order_customer_id');
        $this->forge->dropForeignKey('order','idx_order_shop_id');

        //drop tables
        $this->forge->dropTable('order', true);
        $this->forge->dropTable('order_items', true);
        $this->forge->dropTable('exchange_rates', true);
    }
}
