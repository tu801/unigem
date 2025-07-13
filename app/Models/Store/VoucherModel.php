<?php

/**
 * @author tmtuan
 * created Date: 05-July-2025
 * Project: Unigem
 */

namespace App\Models\Store;

use App\Entities\Store\Voucher;
use CodeIgniter\Model;

class VoucherModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'voucher';
    protected $primaryKey       = 'voucher_id';
    protected $useAutoIncrement = true;
    protected $returnType       = Voucher::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'voucher_code',
        'voucher_title',
        'voucher_description',
        'voucher_discount_type',
        'voucher_discount_value',
        'voucher_minimum_order',
        'currency',
        'voucher_start_date',
        'voucher_end_date',
        'voucher_status', // 0: disable, 1: enable, 2: expired
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function generateVoucherCode()
    {
        do {
            $code = strtoupper(getRandomString(6));
            $check = $this->where('voucher_code', $code)
                ->get()->getFirstRow();
        } while (isset($check->voucher_id) && $check->voucher_id !== 0);

        return $code;
    }
}
