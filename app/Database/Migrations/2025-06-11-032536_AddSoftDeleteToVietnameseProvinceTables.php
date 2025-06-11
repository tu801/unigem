<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSoftDeleteToVietnameseProvinceTables extends Migration
{
    public function up()
    {
        /**
         * Add soft delete column to table: province | district | ward
         */
        $fields = [
            'created_at datetime default current_timestamp',
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ];
        $this->forge->addColumn('provinces', $fields);
        $this->forge->addColumn('districts', $fields);
        $this->forge->addColumn('wards', $fields);
    }

    public function down()
    {
        // drop soft delete column
        $this->forge->dropColumn('provinces', 'created_at');
        $this->forge->dropColumn('provinces', 'updated_at');
        $this->forge->dropColumn('provinces', 'deleted_at');

        $this->forge->dropColumn('districts', 'created_at');
        $this->forge->dropColumn('districts', 'updated_at');
        $this->forge->dropColumn('districts', 'deleted_at');

        $this->forge->dropColumn('wards', 'created_at');
        $this->forge->dropColumn('wards', 'updated_at');
        $this->forge->dropColumn('wards', 'deleted_at');
    }
}