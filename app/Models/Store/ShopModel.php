<?php

namespace App\Models\Store;

use CodeIgniter\Model;
use Modules\Acp\Entities\Store\Shop;

class ShopModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'shop';
    protected $primaryKey       = 'shop_id';
    protected $useAutoIncrement = true;
    protected $returnType       = Shop::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['user_init', 'name', 'phone', 'image', 'province_id', 'district_id', 'ward_id', 'address', 'status'];

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
}
