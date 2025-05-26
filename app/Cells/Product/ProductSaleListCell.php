<?php

namespace App\Cells\Product;

use App\Enums\Store\Product\ProductStatusEnum;
use App\Models\Store\Product\ProductModel;
use CodeIgniter\Config\Services;
use CodeIgniter\View\Cells\Cell;

class ProductSaleListCell extends Cell
{
    protected string $view = 'product_sale_list';
    public $products = [];
    public $currentLang;

    public function mount()
    {
        $session          = Services::session();
        
        $this->currentLang = $session->lang;
        $this->products = model(ProductModel::class)
                            ->select('product.*, pdc.pd_name, pdc.pd_slug, pdc.price, pdc.price_discount ')
                            ->join('product_content AS pdc', 'pdc.product_id = product.id')
                            ->where('pdc.lang_id', $session->lang->id)
                            ->orderBy('pdc.price_discount DESC')
                            ->orderBy('product.id DESC')
                            ->where('pd_status', ProductStatusEnum::PUBLISH)
                            ->findAll(5);
    }

    public function canvas_search_items() {
        $session          = Services::session();
        
        $this->currentLang = $session->lang;
        $products = model(ProductModel::class)
                            ->select('product.*, pdc.pd_name, pdc.pd_slug, pdc.price, pdc.price_discount ')
                            ->join('product_content AS pdc', 'pdc.product_id = product.id')
                            ->where('pdc.lang_id', $session->lang->id)
                            ->orderBy('pdc.price_discount DESC')
                            ->orderBy('product.id DESC')
                            ->where('pd_status', ProductStatusEnum::PUBLISH)
                            ->findAll(3);

        return $this->view('canvas_search_items', [
            'products' => $products,
            'currentLang' => $session->lang
        ]);
    }
}
