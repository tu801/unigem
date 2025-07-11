<?php

namespace Modules\Acp\Controllers\Store\Order;

use App\Traits\Store\UseVoucher;
use Modules\Acp\Controllers\AcpController;

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
}
