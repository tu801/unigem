<?php

namespace Modules\Acp\Enums\Store\Order;

use Modules\Acp\Enums\BaseEnum;

class EPaymentStatus extends BaseEnum
{
    const PAID    = 1;
    const UNPAID  = 2;
    const DEPOSIT = 3;
}