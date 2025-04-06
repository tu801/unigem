<?php

namespace App\Validations;

use Config\Database;
use Modules\Acp\Models\Store\Product\ProductContentModel;
use CodeIgniter\Config\Services;

class ProductNameValidation
{
    /**
     * check if the product slug is exist in table product_content
     * 
     * Example:
     *    checkProductSlugExist[pd_ct_id]       => for edit product form
     *    checkProductSlugExist                 => for add new product form
     *
     * @param mixed $value
     * @param string|null $id
     * @return bool
     */
    public function checkProductSlugExist(mixed $value, ?string $id = null): bool
    {
        $lang = Services::session()->lang;

        $query = model(ProductContentModel::class)
                    ->where('pd_slug', clean_url($value))
                    ->where('lang_id', $lang->id);

        if($id) {
            $query->where("pd_ct_id != {$id}");
        }
        $check = $query->first();
        
        return isset($check->id) ? false : true;
    }

    /**
     * check if the product name is exist in table product_content
     * 
     * Example:
     *    checkProductNameExist[pd_ct_id]   => for edit product form
     *    checkProductNameExist             => for add new product form
     * 
     * @param mixed $value
     * @param string|null $id
     * @return bool
     */
    public function checkProductNameExist(mixed $value, ?string $id ): bool
    {
        $lang = Services::session()->lang;

        $query = model(ProductContentModel::class)
                ->where('pd_name', $value)
                ->where('lang_id', $lang->id);

        if($id) {
            $query->where("pd_ct_id != {$id}");
        }
        $check = $query->first();
        return isset($check->id) ? false : true;
    }
}