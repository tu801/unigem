<?php
/**
 * Author: tmtuan
 * Created date: 8/19/2023
 * Project: nha-khoa-viet-my
 **/

namespace Modules\Acp\Models\Store\Customer;


use CodeIgniter\Model;
use Modules\Acp\Entities\Store\Customer\Customer;

class CustomerModel extends Model
{
    protected $table      = 'customer';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = Customer::class;
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'user_id',
        'cus_code',
        'cus_full_name',
        'cus_email',
        'cus_phone',
        'cus_address',
        'cus_birthday',
        'province_id',
        'district_id',
        'ward_id',
        'active',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validations
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = true;
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
            $code = strtoupper(getRandomString(6));
            $check = $this->where('cus_code', $code)
                    ->get()->getFirstRow();
        } while (isset($check->id) && $check->id !== 0);

        return $code;
    }
}