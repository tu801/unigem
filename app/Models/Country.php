<?php

namespace App\Models;

use CodeIgniter\Model;

class Country extends Model
{
    protected $table            = 'countries';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['code', 'name', 'full_name', 'flags', 'currencies'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

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

    public function getCountries()
    {
        $countriesData = [];
        if (!$countriesData = cache('_countries')) {
            $countries = $this->orderBy('name', 'ASC')->findAll();
            if (!empty($countries) && count($countries) > 0) {
                foreach ($countries as $item) {
                    $countriesData[] = (object)[
                        'id' => $item->id,
                        'code' => $item->code,
                        'name' => $item->name,
                        'full_name' => $item->full_name,
                        'flags' => json_decode($item->flags),
                        'currencies' => json_decode($item->currencies),
                    ];
                }
                cache()->save('_countries', $countriesData);
            }
        }
        return $countriesData;
    }
}
