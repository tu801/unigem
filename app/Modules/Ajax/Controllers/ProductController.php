<?php

/**
 * @author: tmtuan
 * @date: 2025-Apr-14
 */

namespace Modules\Ajax\Controllers;

use App\Models\Store\Product\ProductModel;

class ProductController extends AjaxBaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->_model = model(ProductModel::class);
    }

    public function getProductById($productId)
    {
        $this->_checkSpam();

        $productData = $this->_model
            ->select('product.*, pdc.pd_name, pdc.pd_slug, pdc.pd_weight, pdc.pd_size, pdc.pd_cut_angle, pdc.price, pdc.price_discount')
            ->join('product_content as pdc', 'pdc.product_id = product.id')
            ->where('pdc.lang_id', $this->currentLang->id)
            ->find($productId);
        $product = clone $productData;
        unset($product->images);
        unset($product->feature_image);

        $imageData = [];
        if (!empty($productData->images)) {
            foreach ($productData->images->data as $imageItem) {
                $imageData[] = base_url($imageItem->full_image);
            }
        } else {
            $imageData[] = $productData->feature_image['full'];
        }
        $product->imageData = $imageData;
        $product->lang = $this->currentLang;
        $product->url = $productData->url;
        $product->display_price = $productData->display_price;

        return $this->respond([
            'status' => 200,
            'data' => $product
        ]);
    }
}