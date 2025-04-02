<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class VietnameseProvinceTables extends Migration
{
    public function up()
    {
        // create province table
        $fields = [
            'id'          		=> ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'code'        	    => ['type' => 'varchar', 'constraint' => 20],
            'name'        	    => ['type' => 'varchar', 'constraint' => 255],
            'name_en'        	=> ['type' => 'varchar', 'constraint' => 255],
            'full_name'        	=> ['type' => 'varchar', 'constraint' => 255],
            'full_name_en'      => ['type' => 'varchar', 'constraint' => 255],
            'unit_name'	 		=> ['type' => 'varchar', 'constraint' => 255],
            'unit_name_en'	 	=> ['type' => 'varchar', 'constraint' => 255],
        ];
        $this->forge->addField($fields);
        $this->forge->addKey('id');
        $this->forge->createTable('provinces', true);

        // create district table
        $fields = [
            'id'          		=> ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'province_id'       => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'code'        	    => ['type' => 'varchar', 'constraint' => 20],
            'name'        	    => ['type' => 'varchar', 'constraint' => 255],
            'name_en'        	=> ['type' => 'varchar', 'constraint' => 255],
            'full_name'        	=> ['type' => 'varchar', 'constraint' => 255],
            'full_name_en'      => ['type' => 'varchar', 'constraint' => 255],
            'unit_name'	 		=> ['type' => 'varchar', 'constraint' => 255],
            'unit_name_en'	 	=> ['type' => 'varchar', 'constraint' => 255],
        ];
        $this->forge->addField($fields);
        $this->forge->addKey('id');
        $this->forge->addForeignKey('province_id', 'provinces', 'id', 'CASCADE', 'CASCADE', 'idx_province_id');
        $this->forge->createTable('districts', true);

        // create province table
        $fields = [
            'id'          		=> ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'district_id'       => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'code'        	    => ['type' => 'varchar', 'constraint' => 20],
            'name'        	    => ['type' => 'varchar', 'constraint' => 255],
            'name_en'        	=> ['type' => 'varchar', 'constraint' => 255],
            'full_name'        	=> ['type' => 'varchar', 'constraint' => 255],
            'full_name_en'      => ['type' => 'varchar', 'constraint' => 255],
            'unit_name'	 		=> ['type' => 'varchar', 'constraint' => 255],
            'unit_name_en'	 	=> ['type' => 'varchar', 'constraint' => 255],
        ];
        $this->forge->addField($fields);
        $this->forge->addKey('id');
        $this->forge->addForeignKey('district_id', 'districts', 'id', 'CASCADE', 'CASCADE', 'idx_district_id');
        $this->forge->createTable('wards', true);
    }

    public function down()
    {
        // drop foreign key
        $this->forge->dropForeignKey('districts','idx_province_id');
        $this->forge->dropForeignKey('wards','idx_district_id');

        //drop table
        $this->forge->dropTable('provinces', true);
		$this->forge->dropTable('districts', true);
        $this->forge->dropTable('wards', true);
    }
}
