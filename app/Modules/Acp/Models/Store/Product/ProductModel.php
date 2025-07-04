<?php

/**
 * @author tmtuan
 * created Date: 13-Apr-2025
 */

namespace Modules\Acp\Models\Store\Product;

use App\Models\Store\Product\ProductModel as BaseProductModel;

class ProductModel extends BaseProductModel
{

    public $productQueryFields = [
        'id',
        'cat_id',
        'pd_sku',
        'pd_image',
        'pd_status',
        'product.created_at',
        'product.updated_at',
        'deleted_at',
        'product_content.lang_id',
        'product_content.pd_name',
        'product_content.pd_slug',
        'product_content.pd_weight',
        'product_content.pd_size',
        'product_content.pd_cut_angle',
        'product_content.origin_price',
        'product_content.price',
        'product_content.price_discount',
        'product_content.pd_tags',
        'product_content.pd_description',
        'product_content.product_info',
        'product_content.seo_meta', 
    ];

    /**
     * Recover soft delete post
     */
    public function recover($id)
    {
        $sql = "UPDATE `{$this->table}` SET `deleted_at` = NULL WHERE `id` = {$id}";
        if ($this->db->query($sql)) return true;
        else return false;
    }

    /**
     * Get product item by ID which use to add product to cart
     * @param int $id
     * @return object|null
     */
    public function getProductItemById($id, $lang = null)
    {
        if (!isset($lang->id)) $lang = session()->lang;

        $productItem = $this
            ->select($this->productQueryFields)
            ->join('product_content', 'product_content.product_id = product.id')
            ->where('product_content.lang_id', $lang->id)
            ->find($id);

        return $productItem;
    }
}
