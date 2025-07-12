<?php

namespace Modules\Ajax\Controllers;

use App\Models\Store\Product\ProductModel;
use App\Traits\Store\UseVoucher;

class OrderController extends AjaxBaseController
{
    use UseVoucher;

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
            $item->url = $item->url;
        }

        return $this->respond([
            'status' => 200,
            'data' => $productData
        ]);
    }

    public function applyVoucher()
    {
        $this->checkSpam();
        $voucherCode = $this->request->getGet('voucher_code');

        if (empty($voucherCode)) {
            return $this->respond([
                'status' => 400,
                'message' => lang('Order.voucher_code_required')
            ]);
        }

        // Assuming you have a method to validate the voucher code
        $voucher = model(\App\Models\Store\VoucherModel::class)->validateVoucher($voucherCode);
        if (!$voucher) {
            return $this->respond([
                'status' => 404,
                'message' => lang('Voucher.voucher_not_found')
            ]);
        }

        return $this->respond([
            'status' => 200,
            'data' => $voucher
        ]);
    }
}
