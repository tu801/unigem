<?php
namespace Modules\Acp\Models;

use CodeIgniter\Model;
use Modules\Acp\Enums\Store\ShopEnum;
use Modules\Acp\Models\Store\ProvinceModel;

class ConfigModel extends Model
{
    protected $table = 'config';
    protected $primaryKey = 'id';

    protected $returnType = 'object';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'group_id', 'title', 'key', 'value', 'is_json'
    ];

    protected $useTimestamps = false;

    protected $skipValidation = true;

    const DEFAULT_GROUP = 'default';

    /**
     * List menu location config
     * @return array
     */
    public function getMenuLocation()
    {
        return $this->where(['group_id' => 'default', 'key' => 'menu_location'])->findAll();
    }

    /**
     * create record item if not exist otherwise update the item
     * @param $input
     */
    public function insertOrUpdate($input) {
        $item = $this->where('group_id', $input['group_id'])
                ->where('key', $input['key'])
                ->get()->getFirstRow();
        if ( !empty($item) || isset($item->id) ) {
            $updateData = [
                'value' => $input['value'],
                'is_json' => $input['is_json']??0,
            ];
            $this->update($item->id, $updateData);
        } else {
            $this->insert($input);
        }
    }

    public function convertShippingConfig(&$item)
    {
        $_provinceModel = \model(ProvinceModel::class);
        $locale = session()->lang->locale ?? '';

        if ( isset($item->title) && $item->title > 0 ) {
            $province = $_provinceModel->find($item->title);
            $item->province_name = (!empty($locale) && $locale !== 'vi') ? $province['full_name_'.$locale] : $province['full_name'];
        }
    }

    /**
     * get shipping fee
     * @param $province_id
     * @return int
     */
    public function getShipFee($province_id) {
        $feeProvince = $this->where('group_id', ShopEnum::CONFIG_GROUP)
                            ->where('key', ShopEnum::PROVINCE_SHIP_CONFIG)
                            ->where('title', $province_id)
                            ->first();
        if (isset($feeProvince->id)) {
            return $feeProvince->value;
        }else {
            $feeDefault = $this->where('group_id', ShopEnum::CONFIG_GROUP)
                               ->where('key', ShopEnum::SHIP_CONFIG_KEY)
                               ->first();

            return $feeDefault->value ?? 0;
        }

    }

    public function getShipFeeOnWeight() {
        $feeProvince = $this->where('group_id', ShopEnum::CONFIG_GROUP)
            ->where('key', ShopEnum::WEIGHT_SHIP_CONFIG)
            ->first();
        if (isset($feeProvince->id)) {
            return $feeProvince->value;
        }
        return 0;
    }
}