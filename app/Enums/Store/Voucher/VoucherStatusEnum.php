<?php

namespace App\Enums\Store\Voucher;

use App\Enums\BaseEnum;

class VoucherStatusEnum extends BaseEnum
{
    const ENABLE = 1;
    const DISABLE = 0;
    const EXPIRED = 2;
}
