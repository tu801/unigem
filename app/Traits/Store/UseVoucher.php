<?php

namespace App\Traits\Store;

use App\Enums\Store\Voucher\VoucherDiscountTypeEnum;
use App\Enums\Store\Voucher\VoucherStatusEnum;
use App\Models\Store\VoucherModel;

trait UseVoucher
{
    public function useVoucher($code, &$orderData, $totalAmount = 0)
    {
        $voucherModel = model(VoucherModel::class);
        $voucher = $voucherModel
            ->where('voucher_code', $code)
            ->where('voucher_status', VoucherStatusEnum::ENABLE)
            ->first();

        if (!$voucher) {
            $orderData['discount_amount'] = 0;
            $orderData['voucher_code'] = null;
            return $orderData;
        }
        // Check if voucher is valid for the current order
        if (!$voucher->isValid()) {
            $orderData['discount_amount'] = 0;
            $orderData['voucher_code'] = null;
            return $orderData;
        }
        if ($voucher->currency != $orderData['currency']) {
            $orderData['discount_amount'] = 0;
            $orderData['voucher_code'] = null;
            return $orderData;
        }
        if ($voucher->voucher_minimum_order > 0 && $voucher->voucher_minimum_order > $totalAmount) {
            $orderData['discount_amount'] = 0;
            $orderData['voucher_code'] = null;
            return $orderData;
        }

        // Calculate discount based on voucher type
        $voucherCode = $voucher->voucher_code;
        if ($voucher->voucher_discount_type == VoucherDiscountTypeEnum::FIXED_AMOUNT) {
            $discount = $voucher->voucher_discount_value;
        } elseif ($voucher->voucher_discount_type == VoucherDiscountTypeEnum::PERCENTAGE) {
            $discount = $totalAmount * ($voucher->voucher_discount_value / 100);
        } else {
            $discount = 0; // Default to no discount if type is unknown
        }

        $orderData['discount_amount'] = $discount;
        $orderData['voucher_code'] = $voucherCode;

        return $orderData;
    }
}
