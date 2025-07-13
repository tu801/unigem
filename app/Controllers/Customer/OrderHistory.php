<?php

namespace App\Controllers\Customer;

use App\Enums\UserTypeEnum;
use App\Models\Store\Order\OrderModel;
use App\Traits\SpamFilter;

class OrderHistory extends \App\Controllers\BaseController
{
    use SpamFilter;

    public function __construct()
    {
        parent::__construct();
        $this->_model  = model(OrderModel::class);

        // check customer logged in
        return $this->checkCustomerLoggedIn();

        $this->page_title = lang('Customer.order_history');
    }

    public function listOrder()
    {
        if (!auth()->loggedIn() || $this->user->user_type == UserTypeEnum::ADMIN) {
            return redirect()->route('/');
        }

        return $this->_render('customer/order/order_history', $this->_data);
    }

    public function orderDetail($orderId)
    {
        if (!auth()->loggedIn() || $this->user->user_type == UserTypeEnum::ADMIN) {
            return redirect()->route('/');
        }

        $order = $this->_model->find($orderId);
        if (!$order) {
            return redirect()->back()->with('errors', lang('Order.order_not_found'));
        }

        $this->_data['order'] = $order;
        return $this->_render('customer/order/order_history_detail', $this->_data);
    }
}
