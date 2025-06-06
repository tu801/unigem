<?php

namespace App\Database\Seeds;

use CodeIgniter\CLI\CLI;
use CodeIgniter\Database\Seeder;

class NewProvinceV1 extends Seeder
{
    public function run()
    {
        //=====================================================================
        // For the new tables, we will use the same name as the old tables
        // but follow the update form this repository:
        // https://github.com/ThangLeQuoc/vietnamese-provinces-database (updated at 05/06/2025)
        //
        //=====================================================================
        $db = \Config\Database::connect();
        $dataFile = ROOTPATH . 'app/Database/ProvincesTables/ImportData_v01Mar2025.sql';

        if (file_exists($dataFile)) {
            $sql = file_get_contents($dataFile);
            $db->query($sql);

            CLI::write("Insert '$dataFile' successfully.\n");
        } else {
            CLI::write("SQL file not found: " . $dataFile . "\n", 'error');
        }
    }
}