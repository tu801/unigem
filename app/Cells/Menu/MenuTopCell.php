<?php

namespace App\Cells\Menu;

use CodeIgniter\View\Cells\Cell;

class MenuTopCell extends Cell
{
    protected string $view = 'menu_top';
    public $main_menu;

    public function mount()
    {
        $menuData = get_menu('main_menu');

        if ( isset($menuData->id) ) {
            $this->main_menu = $menuData;
        }
    }

    public function mobileMenu(): string
    {
        $menuData = get_menu('main_menu');

        return $this->view('mobile_menu', [
            'menuData' => $menuData
        ]);
    }
}
