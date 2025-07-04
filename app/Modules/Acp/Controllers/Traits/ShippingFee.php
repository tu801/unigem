<?php

namespace Modules\Acp\Controllers\Traits;

trait ShippingFee
{
    /**
     * Calculate shipping fee based on province and total weight of products.
     * int $province_id
     * float $weightProductTotal
     */
    public function calculateShippingFee($province_id, $weightProductTotal,)
    {
        $shopConfig = config('Shop');
        if (!$shopConfig->enableShippingFee) {
            return 0;
        }

        $shipFeeProvince = $this->_configModel->getShipFee($province_id);
        $shipFeeOnWeight = $this->_configModel->getShipFeeOnWeight();
        $totalShipFee    = ($weightProductTotal * $shipFeeOnWeight) + $shipFeeProvince;

        return $totalShipFee;
    }
}
