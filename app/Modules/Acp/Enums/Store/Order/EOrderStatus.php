<?php

namespace Modules\Acp\Enums\Store\Order;

use Modules\Acp\Enums\BaseEnum;

class EOrderStatus extends BaseEnum
{
    const OPEN      = 1;
    const CONFIRMED = 2;
    const PROCESSED = 3;
    const SHIPPED   = 4;
    const CANCELLED = 5;
    const COMPLETE  = 6;
}