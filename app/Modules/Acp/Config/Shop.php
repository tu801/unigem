<?php

/**
 * @author tmtuan
 * created Date: 04/13/2025
 */

namespace Modules\Acp\Config;

use CodeIgniter\Config\BaseConfig;

class Shop extends BaseConfig
{
    public $productThumbSize =  ['height' => 340, 'width' => 340];

    public $enableShippingFee = false;

    public $defaultCurrency = 'VND';
    public $currencyList = [
        'VND' => 'VND',
        'USD' => 'USD',
        // 'EUR' => 'EUR',
        // 'JPY' => 'JPY',
        // 'GBP' => 'GBP',
    ];
}
