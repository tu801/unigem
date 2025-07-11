<?php

namespace App\Controllers\Order;

use App\Controllers\BaseController;

class Cart extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->page_title = lang('Order.shopping_cart_title');
    }

    /**
     * Show cart page
     *
     * @return string
     */
    public function index()
    {
        $this->_data['recentlyViewedProducts'] = cache()->get('viewedProducts_' . $this->request->getIPAddress());
        return $this->_render('order/cart', $this->_data);
    }

    /**
     * Show checkout page
     *
     * @return string
     */
    public function checkout()
    {
        dd('Checkout page is under construction');
    }
}
