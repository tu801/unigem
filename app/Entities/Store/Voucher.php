<?php

namespace App\Entities\Store;

class Voucher extends \CodeIgniter\Entity\Entity
{
    protected $errorMessage = [];

    protected $casts = [
        'voucher_id' => 'integer',
        'voucher_discount' => 'float',
        'voucher_minimum_order' => 'float',
        'voucher_start_date' => 'datetime',
        'voucher_end_date' => 'datetime',
    ];


    public function isValid()
    {
        $now = date('Y-m-d H:i:s');
        if ($this->voucher_status != 1) {
            $this->errorMessage[] = lang('Shop.voucher_code_not_found');
            return false; // Voucher is not active
        }
        if ($this->voucher_start_date && $this->voucher_start_date > $now) {
            $this->errorMessage[] = lang('Shop.voucher_code_not_found');
            return false; // Voucher not yet started
        }
        if ($this->voucher_end_date && $this->voucher_end_date < $now) {
            $this->errorMessage[] = lang('Shop.voucher_code_not_found');
            return false; // Voucher expired
        }
        return true;
    }

    public function getErrorMessage()
    {
        return implode(', ', $this->errorMessage);
    }
}
