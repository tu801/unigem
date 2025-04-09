<?php

namespace App\Database\Seeds;

use CodeIgniter\CLI\CLI;
use CodeIgniter\Database\Seeder;

class AcpData extends Seeder
{
    public function run()
    {
        CLI::write("===== Start insert default CMS Data: =====");
        $this->call('App\Database\Seeds\CmsData\AcpData');
        $this->call('App\Database\Seeds\CmsData\DefaultConfigs');
        $this->call('App\Database\Seeds\CmsData\ShopInfor');
        CLI::write("===== insert default CMS Data Done! =====");
    }
}
