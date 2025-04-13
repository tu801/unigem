<?php
/**
 * @author tmtuan
 * @github https://github.com/tu801
 * created Date: 11/7/2023
 */

namespace App\Cells\Product;


use CodeIgniter\View\Cells\Cell;
use App\Enums\Store\Product\ProductStatusEnum;
use App\Models\Store\Product\ProductModel;

class ListingByCell extends Cell
{
    protected string $view = 'listting_product_view';
    public $products;
    public $configs;

    public function mount(string $type)
    {
        $this->configs = config('Site');
        $_productModel = model(ProductModel::class);

        switch ($type) {
            case 'new_arrivals':
                $this->products = $_productModel
                    ->where('pd_status', ProductStatusEnum::PUBLISH)
                    ->orderBy('created_at', 'DESC')
                    ->findAll(4);
                break;
            case 'recommended':
                $this->products = $_productModel
                    ->join('order_items', 'order_items.product = product.id')
                    ->where('pd_status', ProductStatusEnum::PUBLISH)
                    ->groupBy('product.id')
                    ->orderBy('total_quantity', 'DESC')
                    ->select('product.*,SUM(order_items.quantity) AS total_quantity')
                    ->findAll(8);
                break;
        }
    }
}