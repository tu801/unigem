<?php
namespace Modules\Acp\Entities\Store\Customer;

use CodeIgniter\Entity\Entity;
use App\Models\Store\DistrictModel;
use App\Models\Store\ProvinceModel;
use App\Models\Store\WardModel;
use App\Models\User\UserModel;

class Customer extends Entity
{
    protected $full_address;

    protected $user_name;

    public function getUserName()
    {
        if (empty($this->id)) {
            throw new \RuntimeException(lang('Cat.entity_error_request'));
        }

        if ( isset($this->attributes['user_id']) ) {
            $userData = model(UserModel::class)->find($this->attributes['user_id']);
            $this->user_name = $userData->username;
            return $this->user_name;
        }
    }

    public function getFullAddress()
    {
        if (empty($this->province_id) || empty($this->district_id) || empty($this->ward_id)) {
            return false;
        }

        $province = model(ProvinceModel::class)->find($this->province_id);
        $district = model(DistrictModel::class)->find($this->district_id);
        $ward     = model(WardModel::class)->find($this->ward_id);

        $this->full_address = $this->cus_address;
        $this->full_address .= isset($ward['id']) ? ', '.$ward['full_name'] : '';
        $this->full_address .= isset($district['id']) ? ', '.$district['full_name'] : '';
        $this->full_address .= isset($province['id']) ? ', '.$province['full_name'] : '';

        return $this->full_address;
    }
}