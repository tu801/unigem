<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddVoucherTable extends Migration
{
    public function up()
    {
        // add table voucher
        $this->forge->addField([
            'voucher_id'                => ['type' => 'bigint', 'unsigned' => true, 'auto_increment' => true],
            'voucher_code'              => ['type' => 'varchar', 'constraint' => 16, 'null' => false, 'comment' => 'voucher code must be unique'],
            'voucher_title'             => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'voucher_description'       => ['type' => 'text', 'null' => true],
            'voucher_discount_type'     => ['type' => 'varchar', 'constraint' => 32, 'null' => true, 'comment' => 'discount by % or price_amount'],
            'voucher_discount_value'    => ['type' => 'decimal', 'constraint' => "14,2", 'unsigned' => true, 'default' => 0],
            'voucher_minimum_order'     => ['type' => 'decimal', 'constraint' => "14,2", 'unsigned' => true, 'default' => 0, 'comment' => 'minimum order amount to apply voucher'],
            'currency'                  => ['type' => 'varchar', 'constraint' => 32, 'null' => false, 'default' => 'VND', 'comment' => 'VND, USD'],
            'voucher_start_date'        => ['type' => 'datetime', 'null' => true],
            'voucher_end_date'          => ['type' => 'datetime', 'null' => true],
            'voucher_status'            => ['type' => 'tinyint', 'constraint' => 1, 'default' => 0, 'comment' => 'voucher status disable = 0 | enable = 1 | expired = 2'],
            'created_at'                   => ['type' => 'datetime', 'null' => true],
            'updated_at'                   => ['type' => 'datetime', 'null' => true],
            'deleted_at'                   => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addKey('voucher_id', true);
        $this->forge->addUniqueKey('voucher_code');
        $this->forge->addKey('voucher_status');
        $this->forge->createTable('voucher', true);
    }

    public function down()
    {
        $this->forge->dropTable('voucher', true);
    }
}
