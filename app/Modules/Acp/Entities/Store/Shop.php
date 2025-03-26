<?php
/**
 * @author tmtuan
 * @github https://github.com/tu801
 * created Date: 10/25/2023
 */

namespace Modules\Acp\Entities\Store;


use CodeIgniter\Entity\Entity;
use CodeIgniter\I18n\Time;
use Modules\Acp\Enums\UploadFolderEnum;
use Modules\Acp\Models\Store\DistrictModel;
use Modules\Acp\Models\Store\ProvinceModel;
use Modules\Acp\Models\Store\WardModel;

class Shop extends Entity
{
    /**
     * Define properties that are automatically converted to Time instances.
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * images url
     * @var array
     */
    protected $images = [];

    /**
     * @var string
     */
    protected $full_address;

    /**
     * get post images url
     * @return array
     * @throws \Exception
     */
    public function getImages() {
        if (empty($this->shop_id)) {
            throw new \RuntimeException(lang('Acp.invalid_entity'));
        }

        $config = config('Acp');
        if (  !empty($this->attributes['image']) ) {
            $mytime = Time::parse($this->attributes['created_at']);
            $this->images = [
                'full' => base_url('uploads/'. UploadFolderEnum::SHOP .'/'.$this->attributes['image']),
                'thumbnail' => base_url('uploads/'. UploadFolderEnum::SHOP.'/thumb/'.$this->attributes['image'])
            ];

        } else {
            $this->images = [
                'full' => base_url($config->noimg),
                'thumbnail' => base_url($config->noimg),
            ];
        }
        return $this->images;
    }

    public function getFullAddress()
    {
        if (empty($this->shop_id)) {
            throw new \RuntimeException(lang('Acp.invalid_entity'));
        }
        $province = model(ProvinceModel::class)->find($this->attributes['province_id']);
        $district = model(DistrictModel::class)->find($this->attributes['district_id']);
        $ward = model(WardModel::class)->find($this->attributes['ward_id']);

        $this->full_address = $this->attributes['address'];
        $this->full_address .= isset($ward['id']) ? ', '.$ward['full_name'] : '';
        $this->full_address .= isset($district['id']) ? ', '.$district['full_name'] : '';
        $this->full_address .= isset($province['id']) ? ', '.$province['full_name'] : '';

        return $this->full_address;
    }
}