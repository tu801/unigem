<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEmailIndexToCustomerTable extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $tblPrefix = $db->getPrefix();

        // fix email field add not null
        $db->query("ALTER TABLE `{$tblPrefix}customer` MODIFY `cus_email` VARCHAR(255) NOT NULL");
        // add email index to customer table
        $db->query("ALTER TABLE `{$tblPrefix}customer` ADD INDEX `idx_customer_cus_email` (`cus_email`)");
    }

    public function down()
    {
        $db = \Config\Database::connect();
        $tblPrefix = $db->getPrefix();
        $db->query("ALTER TABLE `{$tblPrefix}customer` DROP INDEX `idx_customer_cus_email`");
    }
}
