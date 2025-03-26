<?php

namespace App\Cells;

use CodeIgniter\View\Cells\Cell;
use Modules\Acp\Enums\Store\Order\EOrderStatus;
use Modules\Acp\Models\Store\Customer\CustomerModel;
use Modules\Acp\Models\Store\Order\OrderModel;

class EcommerceOverviewCell extends Cell
{
    protected string $view = 'ecommerce_overview';
    public $revenue_in_month = 0;
    public $conversion_rate = 0;
    public $total_order_in_month = 0;
    public $new_customer_in_month = 0;

    public function mount()
    {
        $thisMonth = date('m');
        $thisYear = date('Y');

        $orders = model(OrderModel::class)
            ->where('Month(created_at)', $thisMonth)
            ->where('Year(created_at)', $thisYear)
            ->findAll();

        if ( !empty($orders) && count($orders) ) {
            $this->total_order_in_month = count($orders);
            $completeOrders = 0;
            foreach ($orders as $item) {
                if ( $item->status == EOrderStatus::COMPLETE ) {
                    $this->revenue_in_month += $item->total;
                    $completeOrders += 1;
                }
            }

            $this->conversion_rate = $completeOrders/$this->total_order_in_month*100;
        }

        $this->new_customer_in_month = model(CustomerModel::class)
            ->where('Month(created_at)', $thisMonth)
            ->where('Year(created_at)', $thisYear)
            ->countAll();

    }
}
