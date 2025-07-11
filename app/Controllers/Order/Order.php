<?php

namespace App\Controllers\Order;

use App\Controllers\BaseController;
use App\Enums\Store\Order\EOrderStatus;
use App\Models\Store\Order\OrderModel;

class Order extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->_model = model(OrderModel::class);
    }

    /**
     * Show order success page
     *
     * @return string
     */
    public function orderSuccess($orderCode)
    {
        // Validate order code
        $item = $this->_model->where('code', $orderCode)->first();

        if (!$item || !isset($item->order_id)) {
            return redirect()->route('order_cart')->with('errors', lang('Order.invalid_order'));
        }

        // Check if the order is already completed
        if ($item->status === EOrderStatus::COMPLETE) {
            return redirect()->route('order_cart')->with('errors', lang('Order.invalid_order'));
        }

        // Set page title and data
        $this->page_title = lang('Order.order_success_title');
        $this->_data['order'] = $item;

        // Render the success view
        return $this->_render('order/success', $this->_data);
    }
}
