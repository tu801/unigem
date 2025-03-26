<?php

namespace App\Cells\Menu;

use CodeIgniter\View\Cells\Cell;

class MenuProductsCell extends Cell
{
    protected string $view = 'menu_products';
    public $productMenu;
    public $locale;

    public function mount() {
        $this->locale = session()->lang;

        $menuData = get_menu('hero_menu');

        if ( isset($menuData->id) ) {
            $this->productMenu = $menuData;
        }
    }

    public function mobileMenu(): string
    {
        $menuData = get_menu('hero_menu');

        return $this->view($this->view, [
            'productMenu' => $menuData
        ]);
    }
}
