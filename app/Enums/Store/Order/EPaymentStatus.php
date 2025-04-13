<?php

namespace App\Enums\Store\Order;

use App\Enums\BaseEnum;

class EPaymentStatus extends BaseEnum
{
    const PAID    = 1;
    const UNPAID  = 2;
    const DEPOSIT = 3;
}