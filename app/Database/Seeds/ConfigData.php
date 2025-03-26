<?php

namespace App\Database\Seeds;

use CodeIgniter\CLI\CLI;
use CodeIgniter\Database\Seeder;

class ConfigData extends Seeder
{
    public function run()
    {
        CLI::write('===== start insert default Configs Data =====');
        $this->call('Modules\Acp\Database\Seeds\Configs');
        CLI::write('===== Insert default Configs Done =====');
    }
}
