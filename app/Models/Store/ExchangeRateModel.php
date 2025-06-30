<?php

namespace App\Models\Store;

use CodeIgniter\I18n\Time;
use CodeIgniter\Model;

class ExchangeRateModel extends Model
{

    protected $table = 'exchange_rates';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'currency_from',
        'currency_to',
        'rate',
        'is_active',
        'created_by',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = '';


    /**
     * Lấy tỷ giá hối đoái giữa hai loại tiền tệ
     * Mặc định lấy tỷ giá từ USD sang VND
     * @param string $currencyFrom Mã tiền tệ nguồn (mặc định là 'USD')
     * @param string $currencyTo Mã tiền tệ đích (mặc định là 'VND')
     * @return float|false Tỷ giá hối đoái nếu tìm thấy, false nếu không tìm thấy
     */
    public function getExchangeRate($currencyFrom = 'USD', $currencyTo = 'VND')
    {
        // Lấy tỷ giá từ bảng exchange_rates
        $rate = $this->where('currency_from', $currencyFrom)
            ->where('currency_to', $currencyTo)
            ->where('is_active', 1)
            ->first();

        if ($rate) {
            $lastUpdated = $rate->updated_at ?? $rate->created_at;
            $rate->last_updated = Time::parse($lastUpdated, 'Asia/Ho_Chi_Minh')->format('d/m/Y H:i');
            return $rate;
        }

        return false;
    }
}
