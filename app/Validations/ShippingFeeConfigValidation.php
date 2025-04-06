<?php
/**
 * Author: tmtuan
 * Created date: 10/28/2023
 * Project: Unigem
 **/

namespace App\Validations;


use Modules\Acp\Enums\Store\ShopEnum;
use Modules\Acp\Models\ConfigModel;

class ShippingFeeConfigValidation
{
    public function checkProvinceConfigExist($value, ?string &$error = null): bool
    {
        $check = model(ConfigModel::class)
            ->where('group_id', ShopEnum::CONFIG_GROUP)
            ->where('key', ShopEnum::PROVINCE_SHIP_CONFIG)
            ->where('title', $value)
            ->first();
        return isset($check->id) ? false : true;
    }
}