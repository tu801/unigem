<?php

/**
 * @author tmtuan
 * created Date: 10/24/2023
 * Project: Unigem
 */

namespace Modules\Acp\Controllers\Store;


use Modules\Acp\Controllers\AcpController;
use App\Enums\BankingPaymentEnum;
use App\Models\ConfigModel;
use App\Traits\VietnameseArea;

class AjaxController extends AcpController
{
    use VietnameseArea;

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