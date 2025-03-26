<?php

namespace App\Cells\Product;

use CodeIgniter\View\Cells\Cell;
use Modules\Acp\Enums\Store\Product\ProductStatusEnum;
use Modules\Acp\Models\Store\Product\ProductModel;

class RelatedProductCell extends Cell
{
    protected string $view = 'related_product';
    public $products = [];
    public $configs;

    public function mount(int $cat_id)
    {
        $this->configs = config('Site');

        $pdData = model(ProductModel::class)
            ->where('cat_id', $cat_id)
            ->where('pd_status', ProductStatusEnum::PUBLISH)
            ->orderBy('created_at', 'desc')
            ->findAll();
        if ( isset($pdData) && count($pdData) ) {
            $this->products = $pdData;
        }
    }
}
