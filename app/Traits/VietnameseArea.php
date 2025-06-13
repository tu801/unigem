<?php

namespace App\Traits;

use App\Models\Store\DistrictModel;
use App\Models\Store\ProvinceModel;
use App\Models\Store\WardModel;

trait VietnameseArea
{
    public function getProvinces()
    {
        $response = array();
        $provinceData = model(ProvinceModel::class)->findAll();

        if (!empty($provinceData) && count($provinceData) > 0) {
            $data = [];
            foreach ($provinceData as $item) {
                $data[] = (object)[
                    'id' => $item['id'],
                    'name' => $item['full_name']
                ];
            }
            $response['error'] = 0;
            $response['province'] = $data;
        } else {
            $response['error'] = 1;
            $response['message'] = lang('Acp.item_not_found');
        }
        return $this->response->setJSON($response);
    }

    public function getDistricts($province)
    {
        $response = array();
        if (!isset($province) || $province < 0) {
            $response['error'] = 1;
            $response['text'] = lang('Acp.invalid_request');
        } else {
            $locale = session()->lang->locale ?? '';
            $districtData = model(DistrictModel::class)
                ->where('province_id', $province)
                ->findAll();

            if (isset($districtData) && count($districtData) > 0) {
                $data = [];
                foreach ($districtData as $item) {
                    $itemArr['id'] = $item['id'];
                    $itemArr['name'] = (!empty($locale) && $locale !== 'vi') ? $item['full_name_' . $locale] : $item['full_name'];
                    $data[] = (object)$itemArr;
                }
                $response['error'] = 0;
                $response['district'] = $data;
            } else {
                $response['error'] = 1;
                $response['message'] = lang('Acp.item_not_found');
            }
        }

        return $this->response->setJSON($response);
    }

    public function getWards($district)
    {
        $response = array();
        if (!isset($district) || $district < 0) {
            $response['error'] = 1;
            $response['text'] = lang('Acp.invalid_request');
        } else {
            $locale = session()->lang->locale ?? '';
            $districtData = model(WardModel::class)
                ->where('district_id', $district)
                ->findAll();

            if (isset($districtData) && count($districtData) > 0) {
                $data = [];
                foreach ($districtData as $item) {
                    $itemArr['id'] = $item['id'];
                    $itemArr['name'] = (!empty($locale) && $locale !== 'vi') ? $item['full_name_' . $locale] : $item['full_name'];
                    $data[] = (object)$itemArr;
                }
                $response['error'] = 0;
                $response['ward'] = $data;
            } else {
                $response['error'] = 1;
                $response['message'] = lang('Acp.item_not_found');
            }
        }

        return $this->response->setJSON($response);
    }
}