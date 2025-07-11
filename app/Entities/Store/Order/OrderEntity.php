<?php

namespace App\Entities\Store\Order;

use CodeIgniter\Entity\Entity;
use App\Enums\Store\Order\EOrderStatus;
use App\Models\Country;
use App\Models\LangModel;
use App\Models\Store\DistrictModel;
use App\Models\Store\ProvinceModel;
use App\Models\Store\ShopModel;
use App\Models\Store\WardModel;

class OrderEntity extends Entity
{
    protected $full_delivery_address;
    protected $lang;

    public function getDeliveryInfo()
    {
        return is_null($this->attributes['delivery_info']) ? [] : json_decode($this->attributes['delivery_info']);
    }

    public function getCustomerInfo()
    {
        return is_null($this->attributes['customer_info']) ? [] : json_decode($this->attributes['customer_info']);
    }


    public function getShop()
    {
        if (!isset($this->attributes['shop_id'])) {
            return false;
        }
        $_shopModel = model(ShopModel::class);
        return $_shopModel->where('shop_id', $this->attributes['shop_id'])->first();
    }

    public function getFullDeliveryAddress()
    {
        if (empty($this->delivery_info)) {
            return false;
        }
        $country = model(Country::class)->find($this->delivery_info->country_id);
        $province = model(ProvinceModel::class)->find($this->delivery_info->province_id);
        $district = model(DistrictModel::class)->find($this->delivery_info->district_id);
        $ward     = model(WardModel::class)->find($this->delivery_info->ward_id);

        if ($country->id != 200) {
            $this->full_delivery_address = $this->delivery_info->cus_address . ', ' . $country->name;
            return $this->full_delivery_address;
        }
        $this->full_delivery_address = $this->delivery_info->cus_address;
        $this->full_delivery_address .= isset($ward['id']) ? ', ' . $ward['full_name'] : '';
        $this->full_delivery_address .= isset($district['id']) ? ', ' . $district['full_name'] : '';
        $this->full_delivery_address .= isset($province['id']) ? ', ' . $province['full_name'] : '';

        return $this->full_delivery_address;
    }

    public function getOrderStatusText()
    {
        if (!isset($this->attributes['shop_id'])) {
            return false;
        }
        $statusText = '';
        switch ($this->attributes['status']) {
            case EOrderStatus::OPEN:
                $statusText = lang("Order.order_status_{$this->attributes['status']}");
                break;
            case EOrderStatus::CONFIRMED:
            case EOrderStatus::SHIPPED:
            case EOrderStatus::PROCESSED:
                $statusText = lang("Order.order_status_{$this->attributes['status']}");
                break;
            case EOrderStatus::CANCELLED:
                $statusText = lang("Order.order_status_{$this->attributes['status']}");
                break;
            case EOrderStatus::COMPLETE:
                $statusText = lang("Order.order_status_{$this->attributes['status']}");
                break;
        }

        return $statusText;
    }

    public function getLang()
    {
        if (!isset($this->attributes['lang_id'])) {
            return false;
        }
        if (empty($this->lang)) {
            $this->lang = model(LangModel::class)->find($this->attributes['lang_id']);
        }
        return $this->lang;
    }
}
