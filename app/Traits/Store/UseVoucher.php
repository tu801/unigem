<?php

namespace App\Traits\Store;

use App\Enums\Store\Voucher\VoucherDiscountTypeEnum;
use App\Enums\Store\Voucher\VoucherStatusEnum;
use App\Models\Store\VoucherModel;

trait UseVoucher
{
    /**
     * get Voucher and apply it to the order data.
     */
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

    /**
     * AJAX method to apply voucher code.
     * This method can be used in frontend AJAX calls to validate, check if voucher code is valid and then return voucher data for Ajax request.
     */
    public function ajaxApplyVoucher()
    {
        $code = $this->request->getGet('voucher_code');
        $model = model(\App\Models\Store\VoucherModel::class);
        if (empty($code)) {
            return $this->response->setJSON([
                'code' => 400,
                'message' => lang('Shop.voucher_code_required'),
            ]);
        }

        $voucher = $model->where('voucher_code', $code)
            ->where('voucher_status', VoucherStatusEnum::ENABLE) // Only active vouchers
            ->first();

        if (!$voucher) {
            return $this->response->setJSON([
                'code' => 404,
                'message' => lang('Shop.voucher_code_not_found'),
            ]);
        }

        // Check if voucher is valid for the current order
        if (!$voucher->isValid()) {
            return $this->response->setJSON([
                'code' => 400,
                'message' => $voucher->getErrorMessage(),
            ]);
        }

        // If valid, return voucher details
        return $this->response->setJSON([
            'code' => 200,
            'voucher' => [
                'voucher_id' => $voucher->voucher_id,
                'voucher_code' => $voucher->voucher_code,
                'voucher_title' => $voucher->voucher_title,
                'voucher_description' => $voucher->voucher_description,
                'voucher_discount_type' => $voucher->voucher_discount_type,
                'voucher_discount_value' => $voucher->voucher_discount_value,
                'currency' => $voucher->currency,
                'voucher_start_date' => $voucher->voucher_start_date,
                'voucher_end_date' => $voucher->voucher_end_date,
            ],
        ]);
    }
}
