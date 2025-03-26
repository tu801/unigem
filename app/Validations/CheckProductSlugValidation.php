<?php

namespace App\Validations;

use Modules\Acp\Models\Store\Product\ProductModel;

class CheckProductSlugValidation
{
    public function checkProductSlugExist($value, string $id = null): bool
    {
        $query = model(ProductModel::class)->where('pd_slug', clean_url($value));
        if($id) {
            $query->where("id != {$id}");
        }
        $check = $query->first();
        return isset($check->id) ? false : true;
    }
}