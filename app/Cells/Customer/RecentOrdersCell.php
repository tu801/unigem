<?php

namespace App\Cells\Customer;

use App\Models\CusModel;
use CodeIgniter\View\Cells\Cell;
use Modules\Acp\Enums\Store\Order\EOrderStatus;
use Modules\Acp\Models\Store\Order\OrderModel;

class RecentOrdersCell extends Cell
{
    protected string $view = 'recent_orders';
    public $orders = [];

    public function mount()
    {
        $user = user();
        $customer = model(CusModel::class)->queryCustomerByUserId($user->id)->first();
        $orderData = model(OrderModel::class)
            ->join('order_items', 'order.order_id = order_items.order_id')
            ->groupBy('order.order_id')
            ->select('order.*, COUNT(order_items.order_id) as count_product')
            ->where('customer_id', $customer->id)
            ->whereIn('status', [EOrderStatus::OPEN, EOrderStatus::PROCESSED, EOrderStatus::CONFIRMED])
            ->orderBy('order_id', 'desc')
            ->findAll();

        if ( count($orderData) ) {
            $this->orders = $orderData;
        }
    }

    public function listOrderHistory(): string
    {
        $user = user();
        $customer = model(CusModel::class)->queryCustomerByUserId($user->id)->first();
        $orderData = model(OrderModel::class)
            ->join('order_items', 'order.order_id = order_items.order_id')
            ->groupBy('order.order_id')
            ->select('order.*, COUNT(order_items.order_id) as count_product')
            ->where('customer_id', $customer->id)
            ->orderBy('order_id', 'desc')
            ->findAll();

        return $this->view($this->view, [
            'orders' => $orderData
        ]);
    }

}
