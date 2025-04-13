<?php
/**
 * @author tmtuan
 * created Date: 9/27/2023
 * project: thuthuatonline
 */

namespace App\Cells\Wigets;


use CodeIgniter\Config\Services;
use CodeIgniter\View\Cells\Cell;
use App\Enums\CategoryEnum;
use App\Models\Blog\CategoryModel;

class CategoryListCell extends Cell
{
    protected string $view = 'wg_categories';
    public array $categories;
    public $configs;

    public function mount($configs = null)
    {
        $session          = Services::session();
        $this->configs    = $configs;
        $this->categories = model(CategoryModel::class)->getCategories(CategoryEnum::CAT_TYPE_POST, $session->lang->id);
    }
}