<?php

namespace Modules\Ajax\Controllers;

use App\Models\Store\Product\ProductModel;

class OrderController extends AjaxBaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->_model = model(ProductModel::class);
    }

    /**
     * Get products by Ids
     */
    public function getProducts()
    {
        $this->checkSpam();
        $productIds = $this->request->getGet('product_id');
        if (empty($productIds)) {
            return $this->respond([
                'status' => 400,
                'message' => lang('Product.product_id_required')
            ]);
        }

        $productData = $this->_model
            ->select($this->_model->productQueryFields)
            ->join('product_content', 'product_content.product_id = product.id')
            ->where('product_content.lang_id', $this->currentLang->id)
            ->whereIn('product.id', $productIds)
            ->findAll();

        if (empty($productData)) {
            return $this->respond([
                'status' => 404,
                'message' => lang('Product.product_not_found')
            ]);
        }

        foreach ($productData as $item) {
            $item->feature_image = $item->feature_image;
            $item->product_meta = $item->product_meta;
        }

        return $this->respond([
            'status' => 200,
            'data' => $productData
        ]);
    }
}