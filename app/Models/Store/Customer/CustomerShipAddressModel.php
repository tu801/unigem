<?php

/**
 * @author tmtuan
 * created Date: 21/06/2025
 * Project: Unigem
 */

namespace App\Models\Store\Customer;


use CodeIgniter\Model;

class CustomerShipAddressModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'customer_ship_address';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'cus_id',
        'country_id',
        'province_id',
        'district_id',
        'ward_id',
        'ship_full_name',
        'ship_telephone',
        'ship_address',
        'ship_email',
        'is_default'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = '';

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

    /**
     * Set the default address for a customer.
     * If a previous default address exists, it will be unset.
     *
     * @param int $cus_id
     */
    public function unsetDefaultAddressItem($cus_id) {
        $previousDefaultItem = $this
            ->where('cus_id', $cus_id)
            ->where('is_default', 1)
            ->first();

        if (isset($previousDefaultItem->id)) {
            $previousDefaultItem->is_default = 0;
            $this->save($previousDefaultItem);
        }

    }
}