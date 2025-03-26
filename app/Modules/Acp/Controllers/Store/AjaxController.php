<?php
/**
 * @author tmtuan
 * created Date: 10/24/2023
 * Project: Unigem
 */

namespace Modules\Acp\Controllers\Store;


use Modules\Acp\Controllers\AcpController;
use Modules\Acp\Enums\BankingPaymentEnum;
use Modules\Acp\Models\ConfigModel;
use Modules\Acp\Models\Store\DistrictModel;
use Modules\Acp\Models\Store\ProvinceModel;
use Modules\Acp\Models\Store\WardModel;

class AjaxController extends AcpController
{

    public function getProvinces()
    {
        $response = array();
        $provinceData = model(ProvinceModel::class)->findAll();

        if ( !empty($provinceData) && count($provinceData) > 0 ) {
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
        if ( !isset($province) || $province < 0 ) {
            $response['error'] = 1;
            $response['text'] = lang('Acp.invalid_request');
        } else {
            $locale = session()->lang->locale ?? '';
            $districtData = model(DistrictModel::class)
                ->where('province_id', $province)
                ->findAll();

            if ( isset($districtData) && count($districtData) > 0 ) {
                $data = [];
                foreach ($districtData as $item) {
                    $itemArr['id'] = $item['id'];
                    $itemArr['name'] = (!empty($locale) && $locale !== 'vi') ? $item['full_name_'.$locale] : $item['full_name'];
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
        if ( !isset($district) || $district < 0 ) {
            $response['error'] = 1;
            $response['text'] = lang('Acp.invalid_request');
        } else {
            $locale = session()->lang->locale ?? '';
            $districtData = model(WardModel::class)
                ->where('district_id', $district)
                ->findAll();

            if ( isset($districtData) && count($districtData) > 0 ) {
                $data = [];
                foreach ($districtData as $item) {
                    $itemArr['id'] = $item['id'];
                    $itemArr['name'] = (!empty($locale) && $locale !== 'vi') ? $item['full_name_'.$locale] : $item['full_name'];
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

    /**
     * get bank list
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function getBanks()
    {
        $response          = array();
        $banks             = BankingPaymentEnum::BANK_LIST;
        $response['error'] = 0;
        $response['banks'] = $banks;
        return $this->response->setJSON($response);
    }


    public function getShippingFee()
    {
        $inputData         = $this->request->getGet();
        $response          = array();
        $configModel       = model(ConfigModel::class);
        $data              = [
            'ship_fee_on_weight' => $configModel->getShipFeeOnWeight(),
            'ship_fee_province'  => isset($inputData['province_id']) ? $configModel->getShipFee($inputData['province_id']) : 0,
        ];
        $response['error'] = 0;
        $response['data']  = $data;
        return $this->response->setJSON($response);
    }
}