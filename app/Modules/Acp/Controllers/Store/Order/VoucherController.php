<?php

namespace Modules\Acp\Controllers\Store\Order;

use App\Enums\Store\Voucher\VoucherStatusEnum;
use Modules\Acp\Controllers\AcpController;
use Modules\Acp\Controllers\Traits\UseVoucher;

class VoucherController extends AcpController
{
    use UseVoucher;

    public function __construct()
    {
        parent::__construct();

        if (empty($this->_model)) {
            $this->_model = model(\App\Models\Store\VoucherModel::class);
        }
    }

    public function ajaxApplyVoucher()
    {
        $code = $this->request->getGet('voucher_code');
        if (empty($code)) {
            return $this->response->setJSON([
                'code' => 400,
                'message' => lang('Shop.voucher_code_required'),
            ]);
        }

        $voucher = $this->_model->where('voucher_code', $code)
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
