<?php

/**
 * @author tmtuan
 * created Date: 13-Apr-2025
 */

namespace Modules\Acp\Models\Store\Product;

use App\Models\Store\Product\ProductModel as BaseProductModel;

class ProductModel extends BaseProductModel
{

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