<?php

namespace App\Enums\Store\Order;

use App\Enums\BaseEnum;

class EOrderStatus extends BaseEnum
{
    const OPEN      = 1;
    const CONFIRMED = 2;
    const PROCESSED = 3;
    const SHIPPED   = 4;
    const CANCELLED = 5;
    const COMPLETE  = 6;
}