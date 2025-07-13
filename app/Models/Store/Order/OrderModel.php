<?php

/**
 * @author tmtuan
 * created Date: 10/23/2023
 * Project: Unigem
 */

namespace App\Models\Store\Order;


use CodeIgniter\Model;
use App\Entities\Store\Order\OrderEntity;
use App\Models\Traits\recoverItem;

class OrderModel extends Model
{
    use recoverItem;

    protected $DBGroup          = 'default';
    protected $table            = 'order';
    protected $primaryKey       = 'order_id';
    protected $useAutoIncrement = true;
    protected $returnType       = OrderEntity::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_init',
        'lang_id',
        'customer_id',
        'shop_id',
        'code',
        'title',
        'note',
        'delivery_type',
        'delivery_info',
        'customer_info',
        'status',
        'payment_status',
        'payment_method',
        'currency',
        'exchange_rate',
        'exchange_rate_id',
        'sub_total',
        'voucher_code',
        'discount_amount',
        'shipping_amount',
        'total',
        'total_amount_vnd',
        'customer_paid',
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

    public function generateCode()
    {
        do {
            $code = strtoupper(getRandomString(10));
            $check = $this->where('code', $code)
                ->get()->getFirstRow();
        } while (isset($check->id) && $check->id !== 0);

        return $code;
    }
}
