<?php

namespace Modules\Acp\Controllers\Store;

use App\Models\Store\ExchangeRateModel;
use Config\Database;
use Modules\Acp\Controllers\AcpController;

class ExchangeRateController extends AcpController
{
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->_model = model(ExchangeRateModel::class);
        $this->db                        = Database::connect(); //Load database connection
    }

    public function index()
    {
        $this->_data['title'] = lang('Shop.exchange_rate_manager');

        if ($this->request->getPost()) {
            return $this->saveProfileAction();
        }

        $onlineExchangeRate = new \App\Libraries\CurrencyExchangeRate();
        $this->_data['onlineExchangeRate'] = $onlineExchangeRate->getExchangeRate();
        $this->_data['exchangeRate'] = $this->_model->getExchangeRate();
        $this->_render('\store\exchange_rate\index', $this->_data);
    }

    public function saveProfileAction()
    {
        $postData = $this->request->getPost();

        $validRules = [
            'rate'          => 'required|decimal',
        ];
        $validatiopnMessages = [
            'rate' => [
                'required' => lang('Shop.rate_required'),
                'decimal'  => lang('Shop.rate_decimal'),
            ],
        ];
        if (!$this->validate($validRules, $validatiopnMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $checkRate = $this->_model->getExchangeRate($postData['currency_from'], $postData['currency_to']);
        if (isset($checkRate->id) && $checkRate->id > 0) {
            // So sánh thời gian created_at với thời điểm hiện tại
            $createdAt = $checkRate->created_at ?? $checkRate->updated_at;
            $currentDate = date('Y-m-d');
            $createdDate = date('Y-m-d', strtotime($createdAt));

            if ($createdDate === $currentDate) {
                try {
                    $this->db->transBegin();
                    // Cùng ngày - Update giá trị
                    $this->_model->update($checkRate->id, [
                        'rate'          => $postData['rate'],
                        'updated_at'    => date('Y-m-d H:i:s'),
                    ]);

                    // Log action cho update
                    $logData = [
                        'title'        => lang('Shop.add_exchange_rate_log_title', [$postData['currency_from'], $postData['currency_to']]),
                        'description'  => lang('Shop.add_exchange_rate_log_desc', [$this->user->username, $postData['currency_from'], $postData['currency_to']]),
                        'properties'   => $postData,
                        'subject_id'   => $checkRate->id,
                        'subject_type' => ExchangeRateModel::class,
                    ];
                    $this->logAction($logData);
                    $this->db->transCommit();

                    return redirect()->route('list_exchange_rate')->with('message', lang('Shop.editRateSuccess', [$checkRate->currency_from . '/' . $checkRate->currency_to]));
                } catch (\Exception $e) {
                    $this->db->transRollback();
                    return redirect()->back()->withInput()->with('errors', ['error' => $e->getMessage()]);
                }
            } else {
                try {
                    $this->db->transBegin();
                    $this->_model->update($checkRate->id, [
                        'is_active'     => 0,
                        'updated_at'    => date('Y-m-d H:i:s'),
                    ]);

                    $exchangeRateId = $this->createExchangeRate($postData);
                    $this->db->transCommit();

                    return redirect()->route('list_exchange_rate')->with('message', lang('Shop.addRateSuccess', [$postData['currency_from'] . '/' . $postData['currency_to']]));
                } catch (\Exception $e) {
                    $this->db->transRollback();
                    return redirect()->back()->withInput()->with('errors', ['error' => $e->getMessage()]);
                }
            }
        } else {
            try {
                $exchangeRateId = $this->createExchangeRate($postData);
                return redirect()->route('list_exchange_rate')->with('message', lang('Shop.addSuccess', [$postData['currency_from'] . '/' . $postData['currency_to']]));
            } catch (\Exception $e) {
                return redirect()->back()->withInput()->with('errors', ['error' => $e->getMessage()]);
            }
        }
    }

    private function createExchangeRate($postData)
    {
        $this->_model->insert([
            'currency_from' => $postData['currency_from'],
            'currency_to'   => $postData['currency_to'],
            'rate'          => $postData['rate'],
            'is_active'     => 1,
        ]);
        $exchangeRateId = $this->_model->insertID();

        // Log action cho insert mới
        $logData = [
            'title'        => lang('Shop.add_exchange_rate_log_title', [$postData['currency_from'], $postData['currency_to']]),
            'description'  => lang('Shop.add_exchange_rate_log_desc', [$this->user->username, $postData['currency_from'], $postData['currency_to']]),
            'properties'   => $postData,
            'subject_id'   => $exchangeRateId,
            'subject_type' => ExchangeRateModel::class,
        ];
        $this->logAction($logData);

        return $exchangeRateId;
    }
}
