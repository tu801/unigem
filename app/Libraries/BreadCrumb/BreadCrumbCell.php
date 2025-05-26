<?php
/**
 * @author tmtuan
 * @github https://github.com/tu801
 * created Date: 11/7/2023
 */

namespace App\Libraries\BreadCrumb;

use CodeIgniter\View\Cells\Cell;

class BreadCrumbCell extends Cell
{
    protected string $view = 'bread_crumb';

    public static $breadcrumbs = [];

    public static function add($title, $href)
    {
        if (!$title OR !$href) return;
        self::$breadcrumbs[] = array('title' => $title, 'href' => $href);
    }

    public function render(): string
    {
        $total = count(self::$breadcrumbs);
        return $this->view($this->view, [
            'breadcrumbs' => self::$breadcrumbs,
            'total' => $total
        ]);
    }

    public function product(){
        $total = count(self::$breadcrumbs);
        return $this->view('product_bread_crumb', [
            'breadcrumbs' => self::$breadcrumbs,
            'total' => $total
        ]);
    }
}
