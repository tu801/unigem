<?php

namespace App\Enums\Store\Product;


use App\Enums\BaseEnum;

class ProductStatusEnum extends BaseEnum
{
    const DRAFT   = 3;
    const PENDING = 2;

    // sản phẩm đang bán
    const PUBLISH = 1;

    // sản phẩm ngừng bán
    const STOP = 4;
}