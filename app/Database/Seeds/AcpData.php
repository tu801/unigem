<?php

namespace App\Database\Seeds;

use CodeIgniter\CLI\CLI;
use CodeIgniter\Database\Seeder;

class AcpData extends Seeder
{
    public function run()
    {
        $this->call('Modules\Acp\Database\Seeds\PermissionData');

        $this->call('Modules\Acp\Database\Seeds\AcpData');
        CLI::write("===== insert default Acp Data Done! =====");
    }
}
