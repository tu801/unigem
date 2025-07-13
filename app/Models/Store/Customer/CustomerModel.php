<?php

/**
 * Author: tmtuan
 * Created date: 13-Apr-2025
 **/

namespace App\Models\Store\Customer;


use CodeIgniter\Model;
use App\Entities\Store\Customer\Customer;

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
        'country_id',
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

    public function queryCustomerByUserId($user_id)
    {
        $this->where('user_id', $user_id);

        return $this;
    }

    /**
     * Create a new customer for an order
     * @param array $inputData
     * @return Customer|null
     * @throws \CodeIgniter\Database\Exceptions\DataException
     */
    public function createOrderCustomer($inputData)
    {
        $customerData = [
            'cus_code'      => $this->generateCode(),
            'cus_full_name' => $inputData['full_name'],
            'cus_phone'     => $inputData['phone'],
            'cus_email'     => $inputData['email'] ?? null,
            'country_id'    => $inputData['country_id'] ?? 0,
            'province_id'   => $inputData['province_id'] ?? 0,
            'district_id'   => $inputData['district_id'] ?? 0,
            'ward_id'       => $inputData['ward_id'] ?? 0,
            'cus_address'   => $inputData['address'],
        ];
        
        $customerID = $this->insert($customerData);

        return $this->find($customerID);
    }
}
