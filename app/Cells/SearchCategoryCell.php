<?php

namespace App\Cells;

use CodeIgniter\View\Cells\Cell;
use App\Enums\CategoryEnum;
use App\Models\Blog\CategoryModel;

class SearchCategoryCell extends Cell
{
    protected string $view = 'search_category';
    public $categories = [];

    public function mount()
    {
        $lang = session()->lang;
        $catData = model(CategoryModel::class)
            ->select('category.*, category_content.title, category_content.slug')
            ->join('category_content', 'category_content.cat_id = category.id')
            ->where('category.cat_type', CategoryEnum::CAT_TYPE_PRODUCT)
            ->where('category.parent_id', 0)
            ->where('category.cat_status', CategoryEnum::CAT_STATUS_PUBLISH)
            ->where('category_content.lang_id', $lang->id)
            ->findAll();

        if ( isset($catData) && count($catData) ) {
            $this->categories = $catData;
        }
    }
}
