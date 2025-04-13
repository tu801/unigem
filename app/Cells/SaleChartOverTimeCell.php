<?php

namespace App\Cells;

use CodeIgniter\View\Cells\Cell;
use Libraries\Collection\Collection;
use App\Enums\Store\Order\EOrderStatus;
use App\Enums\Store\Order\EPaymentStatus;
use App\Models\Store\Order\OrderModel;

class SaleChartOverTimeCell extends Cell
{
    protected string $view = 'sale_chart_over_time';
    public $configs;
    public $month_name = [];
    public $monthly_revenue = [];
    public $total_revenue = 0;

    public function mount($configs = null)
    {
        $this->configs = $configs;

        // ger monthly revenue in this year
        $saleData = model(OrderModel::class)
            ->select('DATE_FORMAT(created_at, \'%m\') AS month')
            ->select('SUM(total) AS monthly_revenue')
            ->where('Year(created_at)', date('Y'))
            ->where('status', EOrderStatus::COMPLETE)
            ->where('payment_status', EPaymentStatus::PAID)
            ->groupBy('DATE_FORMAT(created_at, \'%m\')')
            ->get()->getResultArray();

        $collection = new Collection($saleData);
        for($m=1; $m<=12; ++$m){
            $this->month_name[] = date('M', mktime(0, 0, 0, $m, 1));

            $item = $collection->find(function ($item) use($m) {
                return ( $item['month'] == $m ) ? $item['monthly_revenue'] : 0;
            });
            $this->monthly_revenue[] = $item['monthly_revenue'] ?? 0 ;
        }

        $revenue = new Collection($this->monthly_revenue);
        $this->total_revenue = $revenue->sum();
    }
}
