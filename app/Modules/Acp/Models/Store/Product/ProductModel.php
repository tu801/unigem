<?php
/**
 * @author tmtuan
 * created Date: 13-Apr-2025
 */

namespace Modules\Acp\Models\Store\Product;

use App\Models\Store\Product\ProductModel as BaseProductModel;

class ProductModel extends BaseProductModel {
    /**
     * Recover soft delete post
     */
    public function recover($id) {
        $sql = "UPDATE `{$this->table}` SET `deleted_at` = NULL WHERE `id` = {$id}";
        if ( $this->db->query($sql) ) return true;
        else return false;
    }
}