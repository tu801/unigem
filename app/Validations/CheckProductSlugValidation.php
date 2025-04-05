<?php

namespace App\Validations;

use Modules\Acp\Models\Store\Product\ProductContentModel;

class CheckProductSlugValidation
{
    public function checkProductSlugExist(mixed $value, ?string $id = null): bool
    {
        $query = model(ProductContentModel::class)->where('pd_slug', clean_url($value));
        if($id) {
            $query->where("id != {$id}");
        }
        $check = $query->first();
        return isset($check->id) ? false : true;
    }
}