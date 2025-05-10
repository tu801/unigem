<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ShopTables extends Migration
{
    public function up()
    {
        //add table shop
        $this->forge->addField([
            'shop_id'              => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_init'            => ['type' => 'int', 'unsigned' => true],
            'name'                 => ['type' => 'varchar', 'constraint' => 255],
            'phone'                => ['type' => 'varchar', 'constraint' => 20],
            'image'                => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'province_id'          => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'district_id'          => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'ward_id'              => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'address'              => ['type' => 'varchar', 'constraint' => 255],
            'status'               => ['type' => 'tinyint', 'constraint' => 1, 'default' => 1],
            'created_at'           => ['type' => 'datetime', 'null' => true],
            'updated_at'           => ['type' => 'datetime', 'null' => true],
            'deleted_at'           => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addKey('shop_id', true);
        $this->forge->addKey('province_id');
        $this->forge->addKey('district_id');
        $this->forge->addKey('ward_id');
        $this->forge->addForeignKey('user_init', 'users', 'id', 'CASCADE', 'CASCADE', 'idx_shop_user_init');
        $this->forge->createTable('shop', true);


        //add table product
        $this->forge->addField([
            'id'                    => ['type' => 'bigint', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_init'             => ['type' => 'int', 'unsigned' => true],
            'cat_id'                => ['type' => 'int', 'unsigned' => true],
            'pd_sku'                => ['type' => 'varchar', 'constraint' => 64],
            'pd_image'              => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'pd_status'             => ['type' => 'tinyint', 'constraint' => 1, 'default' => 0],
            'minimum'               => ['type' => 'int', 'constraint' => 11, 'default' => 1],
            'publish_date'          => ['type' => 'datetime', 'null' => true],
            'created_at'            => ['type' => 'datetime', 'null' => true],
            'updated_at'            => ['type' => 'datetime', 'null' => true],
            'deleted_at'            => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('user_init');
        $this->forge->addKey('pd_sku');
        $this->forge->addForeignKey('user_init', 'users', 'id', 'CASCADE', 'CASCADE', 'idx_product_user_init');
        $this->forge->addForeignKey('cat_id', 'category', 'id', 'CASCADE', 'CASCADE', 'idx_category_id');
        $this->forge->createTable('product', true);

        //add table product content
        $this->forge->addField([
            'pd_ct_id'              => ['type' => 'bigint', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'product_id'            => ['type' => 'bigint', 'constraint' => 11, 'unsigned' => true],
            'lang_id'               => ['type' => 'int',  'unsigned' => true],
            'pd_name'               => ['type' => 'varchar', 'constraint' => 255],
            'pd_slug'               => ['type' => 'varchar', 'constraint' => 255],
            'pd_weight'             => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'pd_size'               => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'pd_cut_angle'          => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'origin_price'          => ['type' => 'decimal', 'constraint' => "12,6", 'default' => 0],
            'price'                 => ['type' => 'decimal', 'constraint' => "12,6", 'default' => 0],
            'price_discount'        => ['type' => 'decimal', 'constraint' => "12,6", 'default' => 0],
            'pd_tags'               => ['type' => 'json', 'null' => true],
            'pd_description'        => ['type' => 'text'],
            'product_info'          => ['type' => 'longtext'],
            'seo_meta'              => ['type' => 'json', 'null' => true],
            'created_at'            => ['type' => 'datetime', 'null' => true],
            'updated_at'            => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addKey('pd_ct_id', true);
        $this->forge->addKey(['product_id', 'lang_id']);
        $this->forge->addKey('pd_name');
        $this->forge->addKey('pd_slug');
        $this->forge->addKey('lang_id');
        $this->forge->addForeignKey('product_id', 'product', 'id', 'CASCADE', 'CASCADE', 'idx_product_id');
        $this->forge->createTable('product_content', true);
    }

    public function down()
    {
        // drop foreign key
        $this->forge->dropForeignKey('shop', 'idx_shop_user_init');
        $this->forge->dropForeignKey('product', 'idx_product_user_init');
        $this->forge->dropForeignKey('product', 'idx_category_id');
        $this->forge->dropForeignKey('product_content', 'idx_product_id');

        //drop table
        $this->forge->dropTable('shop', true);
        $this->forge->dropTable('product_manufacturer', true);
        $this->forge->dropTable('product', true);
        $this->forge->dropTable('product_content', true);
    }
}