<?php

namespace Modules\Acp\Controllers\Traits;

trait UseVoucher
{
    public function useVoucher($code, &$orderData)
    {
        $voucherModel = model('Modules\Acp\Models\Store\Voucher\VoucherModel');
        $voucher = $voucherModel
            ->join('customer_voucher', 'customer_voucher.voucher_id = promotion_voucher.voucher_id', 'LEFT')
            ->where('voucher_code', $code)
            ->first();

        if (
            isset($voucher) && $voucher->voucher_discount_type != PromotionEnum::DISCOUNT_TYPE_FREE_GIFT &&
            $voucher->voucher_status == EVoucherStatus::UNUSED
        ) {
            if ($voucher->voucher_discount_type == PromotionEnum::DISCOUNT_TYPE_PERCENT) {
                $discount = $totalAmount * ($voucher->voucher_discount_value / 100);
            }
            if ($voucher->voucher_discount_type == PromotionEnum::DISCOUNT_TYPE_VALUE) {
                $discount = $voucher->voucher_discount_value;
            }

            $dataOrder['discount_amount'] = $discount;
            $dataOrder['voucher_code'] = $code;
            $this->_promotionVoucherModel->where('voucher_code', $voucherCode)->set(['voucher_status' => EVoucherStatus::USED])->update();
        }

        $orderData['discount_amount'] = 0;
        $dataOrder['voucher_code'] = null;

        return $orderData;
    }
}
