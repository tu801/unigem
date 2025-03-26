<?php
/**
 * @author tmtuan
 * @github https://github.com/tu801
 * created Date: 10/26/2023
 */

namespace Modules\Acp\Enums\Store;


use Modules\Acp\Enums\BaseEnum;

class ShopEnum extends BaseEnum
{
    const STATUS = [
        'active'    => 1,
        'in_active' => 0,
    ];

    /**
     * shipping config group
     */
    const CONFIG_GROUP = 'shipping_config';

    /**
     * shipping config key
     */
    const SHIP_CONFIG_KEY = 'default_shipping_fee';
    const PROVINCE_SHIP_CONFIG = 'province_shipping_fee';
    const WEIGHT_SHIP_CONFIG = 'weight_ship_config';

    public static function _getStatusKey($val)
    {
        foreach (self::STATUS as $key => $item) {
            if ($item === $val) return $key;
        }
    }
}