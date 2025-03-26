<?php
/**
 * @author tmtuan
 * @github https://github.com/tu801
 * created Date: 11/8/2023
 */

namespace App\Models;

use App\Entities\CusEntity;
use Modules\Acp\Models\Store\Customer\CustomerModel as Customer;

class CusModel extends Customer
{
    protected $returnType = CusEntity::class;

    public function queryCustomerByUserId($user_id)
    {
        $this->where('user_id', $user_id);

        return $this;
    }

    public function createCustomer(CusEntity $cus)
    {
        if ( !isset($cus->active) ) {
            $cus->active = 1;
        }

        try {
            $cusId = $this->insert($cus);
            return $cusId;
        } catch (\Exception $err) {
            log_message('error', '[ERROR] {exception}', ['exception' => $err]);
        }
    }
}