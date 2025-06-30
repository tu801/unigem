<?php

namespace App\Libraries;

class CurrencyExchangeRate
{
    protected $apiUrl = 'https://open.er-api.com/v6/latest/';
    protected $client;
    public $errorMessage;

    public function __construct()
    {
        $this->client = \Config\Services::curlrequest();
    }

    /**
     * Lấy tỷ giá hối đoái từ API
     * Mặc định lấy tỷ giá từ USD sang VND
     * @param string $currencyFrom Mã tiền tệ nguồn (mặc định là 'USD')
     * @param string $currencyTo Mã tiền tệ đích (mặc định là 'VND')
     * @return float|false Tỷ giá hối đoái nếu thành công, false nếu thất bại
     */
    public function getExchangeRate($currencyFrom = 'USD', $currencyTo = 'VND')
    {
        // API dùng open.er-api.com
        $response = $this->client->get($this->apiUrl . $currencyFrom);

        if ($response->getStatusCode() !== 200) {
            $this->errorMessage = "Failed to fetch exchange rate.\n";
            return false;
        }

        $data = json_decode($response->getBody(), true);
        $rate = $data['rates'][$currencyTo] ?? null;

        if (!$rate) {
            $this->errorMessage = "{$currencyTo} rate not found in API response.\n";
            return false;
        }

        return $rate;
    }

    public function convertCurrency($amount, $currencyFrom, $currencyTo)
    {
        $rate = $this->getExchangeRate($currencyFrom, $currencyTo);
        if ($rate) {
            return round($amount * $rate, 2);
        }
        return null;
    }
}
