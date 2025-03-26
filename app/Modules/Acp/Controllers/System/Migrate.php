<?php
/**
 * @author tmtuan
 * created Date: 09-Aug-20
 */

namespace Modules\Acp\Controllers\System;

use Modules\Acp\Controllers\AcpController;

class Migrate extends \CodeIgniter\Controller
{

    public function index()
    {
        $inputData = $this->request->getGet();
        /**
         * Update 29-Aug-2020
         * add new table meta_data
         */
        if ( isset($inputData['cmd']) && $inputData['cmd'] ==='up') command("migrate -all");
        if ( isset($inputData['cmd']) && $inputData['cmd'] ==='down') {
            if ( isset($inputData['b']) && isset($inputData['b']) > 0 ) command("migrate:rollback -b {$inputData['b']}");
        }


        die("command [ {$inputData['cmd']} ] had been run!");
    }

}