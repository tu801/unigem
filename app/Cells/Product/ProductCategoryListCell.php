<?php

namespace App\Cells\Product;

use App\Models\Blog\CategoryModel;
use CodeIgniter\Config\Services;
use CodeIgniter\View\Cells\Cell;

class ProductCategoryListCell extends Cell
{
    public $product_category;
    protected string $view = 'product_category_list';

    public function mount()
    {
        $session          = Services::session();
        
        $this->product_category = model(CategoryModel::class)->getProductCategoriesWithProductCount($session->lang->id);
    }

}
