<?php

namespace App\Cells\Menu;

use CodeIgniter\View\Cells\Cell;

class MenuFooterCell extends Cell
{
    protected string $view = 'menu_footer';

    public function first(): string
    {
        $menuData = get_menu('footer_menu_1');

        if ( isset($menuData->id) ) {
            return $this->view($this->view, [
                'menuData' => $menuData
            ]);
        } else {
            return '';
        }
    }

    public function second()
    {
        $menuData = get_menu('footer_menu_2');

        if ( isset($menuData->id) ) {
            return $this->view($this->view, [
                'menuData' => $menuData
            ]);
        } else {
            return '';
        }
    }
}
