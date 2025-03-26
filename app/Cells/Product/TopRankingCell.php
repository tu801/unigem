<?php
/**
 * @author tmtuan
 * @github https://github.com/tu801
 * created Date: 11/7/2023
 */

namespace App\Cells\Product;


use CodeIgniter\View\Cells\Cell;
use Modules\Acp\Enums\Store\Product\ProductStatusEnum;
use Modules\Acp\Models\Blog\CategoryContentModel;
use Modules\Acp\Models\Store\Product\ProductModel;

class TopRankingCell extends Cell
{
    protected string $view = 'top_ranking_list';

    public $products;
    public $category;
    public $configs;

    public function mount($column_key)
    {
        $this->configs = config('Site');
        $_productModel = model(ProductModel::class);
        $_categoryContentsModel = model(CategoryContentModel::class);
        $themeConfig = get_theme_config($column_key);

        $this->products = $themeConfig
            ? $_productModel
                ->where('pd_status', ProductStatusEnum::PUBLISH)
                ->where('product.cat_id', $themeConfig)
                ->orderBy('created_at', 'DESC')
                ->findAll(3)
            : [];

        $this->category = $_categoryContentsModel
            ->where('cat_id', $themeConfig)->first();
    }
}