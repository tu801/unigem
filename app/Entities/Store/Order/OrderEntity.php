<?php
namespace App\Entities\Store\Order;

use CodeIgniter\Entity\Entity;
use App\Enums\Store\Order\EOrderStatus;
use App\Models\Store\DistrictModel;
use App\Models\Store\ProvinceModel;
use App\Models\Store\ShopModel;
use App\Models\Store\WardModel;

class OrderEntity extends Entity
{
    protected $full_address_delivery;

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

    public function getFullAddressDelivery()
    {
        if (empty($this->delivery_info)) {
            return false;
        }
        $province = model(ProvinceModel::class)->find($this->delivery_info->province_id);
        $district = model(DistrictModel::class)->find($this->delivery_info->district_id);
        $ward     = model(WardModel::class)->find($this->delivery_info->ward_id);

        $this->full_address_delivery = $this->delivery_info->address;
        $this->full_address_delivery .= isset($ward['id']) ? ', '.$ward['full_name'] : '';
        $this->full_address_delivery .= isset($district['id']) ? ', '.$district['full_name'] : '';
        $this->full_address_delivery .= isset($province['id']) ? ', '.$province['full_name'] : '';

        return $this->full_address_delivery;
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
}