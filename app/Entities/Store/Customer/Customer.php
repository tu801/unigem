<?php
namespace App\Entities\Store\Customer;

use App\Models\Country;
use App\Models\Store\Customer\CustomerShipAddressModel;
use CodeIgniter\Entity\Entity;
use App\Models\Store\DistrictModel;
use App\Models\Store\ProvinceModel;
use App\Models\Store\WardModel;
use App\Models\User\UserModel;
use CodeIgniter\I18n\Time;

class Customer extends Entity
{
    protected $full_address;

    protected $user_name;

    protected $shippingAddress = [];

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

    /**
     * Get the full address of the customer.
     * if the country is Vietnam (country_id == 200), include province, district, and ward.
     * If not, return only the cus_address.
     *
     * @return string
     */
    public function getFullAddress()
    {
        if ( $this->country_id == 200 ) {
            $province = model(ProvinceModel::class)->find($this->province_id);
            $district = model(DistrictModel::class)->find($this->district_id);
            $ward     = model(WardModel::class)->find($this->ward_id);

            $this->full_address = $this->cus_address;
            $this->full_address .= isset($ward['id']) ? ', '.$ward['full_name'] : '';
            $this->full_address .= isset($district['id']) ? ', '.$district['full_name'] : '';
            $this->full_address .= isset($province['id']) ? ', '.$province['full_name'] : '';
        } else {
            $this->full_address = $this->cus_address;
        }

        return $this->full_address;
    }

    public function getCusBirthday()
    {
        if ( empty($this->attributes['cus_birthday']) ) {
            return '';
        }

        return Time::parse($this->attributes['cus_birthday'])->format('d-m-Y');
    }

    public function getShippingAddress()
    {
        $shippingAddressModel = model(CustomerShipAddressModel::class);
        $countryModel = model(Country::class);
        $provinceModel = model(ProvinceModel::class);
        $districtModel = model(DistrictModel::class);
        $wardModel    = model(WardModel::class);

        $shippingAddressData = $shippingAddressModel->where('cus_id', $this->id)
            ->orderBy('id', 'DESC')
            ->findAll();

        if ( !empty($shippingAddressData) ) {
            foreach ($shippingAddressData as $address) {
                $full_address = '';

                if ( $address->country_id == 200 ) {
                    $country = $countryModel->find($address->country_id);
                    $province = $provinceModel->find($address->province_id);
                    $district = $districtModel->find($address->district_id);
                    $ward     = $wardModel->find($address->ward_id);

                    $full_address .= $address->ship_address;
                    $full_address .= isset($ward['id']) ? ', '.$ward['full_name'] : '';
                    $full_address .= isset($district['id']) ? ', '.$district['full_name'] : '';
                    $full_address .= isset($province['id']) ? ', '.$province['full_name'] : '';
                    $full_address .= isset($country['id']) ? ', '.$country['full_name'] : '';
                } else {
                    $full_address = $address->ship_address;
                }

                $this->shippingAddress[] = [
                    'id' => $address->id,
                    'ship_full_name' => $address->ship_full_name,
                    'ship_telephone' => $address->ship_telephone,
                    'ship_address' => $address->ship_address,
                    'ship_email' => $address->ship_email,
                    'country_id' => $address->country_id,
                    'province_id' => $address->province_id,
                    'district_id' => $address->district_id,
                    'ward_id' => $address->ward_id,
                    'full_address' => $full_address,
                ];
            }
        }

        return $this->shippingAddress;
    }
}