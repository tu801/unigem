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
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = '';
    protected $updatedField  = '';
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
}