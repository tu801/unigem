<?php

namespace App\Database\Migrations;

use CodeIgniter\CLI\CLI;
use CodeIgniter\Database\Migration;

class RefactorVietnameseProvinceTables extends Migration
{
    public function up()
    {
        //============ Handle Old Tables =============//
        // drop foreign key
        $this->forge->dropForeignKey('districts', 'idx_province_id');
        $this->forge->dropForeignKey('wards', 'idx_district_id');

        // drop old table of VietnameseProvinceTables
        $this->forge->dropTable('provinces', true);
        $this->forge->dropTable('districts', true);
        $this->forge->dropTable('wards', true);

        //============ Handle New Tables =============//
        // For the new tables, we will use the same name as the old tables
        // but follow the update form this repository:
        // https://github.com/ThangLeQuoc/vietnamese-provinces-database (updated at 05/06/2025)
        //
        // create and insert data for provinces table using mysql data file
        $db = \Config\Database::connect();
        $createTablesFilePath = ROOTPATH . 'app/Database/ProvincesTables/CreateTables_v1.sql';

        if (file_exists($createTablesFilePath)) {
            $sql = file_get_contents($createTablesFilePath);

            // Execute each SQL query in the file
            $queries = explode(";", $sql);

            foreach ($queries as $query) {
                $query = trim($query);
                if (!empty($query)) {
                    $db->query($query);
                }
            }
            CLI::write("Create 'VietnameseProvinceTables' successfully.\n");
            // insert data for provinces table using seeder

        } else {
            CLI::write("SQL file not found: " . $createTablesFilePath . "\n", 'error');
        }
    }

    public function down()
    {
        //============ Handle Old Tables =============//
        // restore old table of VietnameseProvinceTables
        $migrate = \Config\Services::migrations();

        $filePath = ROOTPATH . 'app/Database/Migrations/2025-04-02-135302_VietnameseProvinceTables.php';
        $namespace = 'App\Database\Migrations';

        if (file_exists($filePath)) {
            try {
                $migrate->force($filePath, $namespace);
                CLI::write("Migration file '$filePath' executed successfully.\n");

                // restore data
                $seeder = \Config\Database::seeder();
                $seeder->call('ProvinceData');
                CLI::write("Seeder file 'VietnameseProvinceTables' executed successfully.\n");
            } catch (\Throwable $e) {
                CLI::write("Error executing migration: " . $e->getMessage() . "\n");
            }
        } else {
            CLI::write("Migration file '$filePath' not found.\n");
        }
    }
}