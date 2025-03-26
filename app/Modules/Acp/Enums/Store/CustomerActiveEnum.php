<?php
/**
 * Author: tmtuan
 * Created date: 11/19/2023
 * Project: Unigem
 **/

namespace Modules\Acp\Enums\Store;


/**
 * customer active status for table
 * customer.active
 * if customer had user account the user.active status will be the same as customer.active
 * Class CustomerActiveEnum
 * @package Modules\Acp\Enums\Store
 */
class CustomerActiveEnum
{
    const ACTIVE = 1;
    const INACTIVE = 0;
}