<?php

namespace Modules\Acp\Controllers\Store;

use App\Models\Store\ExchangeRateModel;
use Modules\Acp\Controllers\AcpController;

class ExchangeRateController extends AcpController
{

    public function __construct()
    {
        parent::__construct();
        $this->_model = model(ExchangeRateModel::class);
    }

    public function index()
    {
        $this->_data['title'] = lang('Shop.exchange_rate_manager');

        if ($this->request->getPost()) {
            dd($this->request->getPost());
            return $this->saveProfileAction();
        }

        $this->_data['exchangeRates'] = $this->_model->findAll();
        $this->_render('\store\exchange_rate\index', $this->_data);
    }
}