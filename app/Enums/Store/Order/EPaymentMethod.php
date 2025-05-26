<?php

namespace App\Enums\Store\Order;

use App\Enums\BaseEnum;

class EPaymentMethod extends BaseEnum
{
    const BANK_TRANSFER = 1;
    const CASH          = 2;
}