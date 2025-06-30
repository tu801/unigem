<?php

namespace App\Database\Seeds;

use App\Libraries\CurrencyExchangeRate;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class ExchangeRateSeed extends Seeder
{
    public function run()
    {
        $exchangeClient = new CurrencyExchangeRate();

        $rate = $exchangeClient->getExchangeRate();

        if (!$rate) {
            CLI::error("Failed to fetch exchange rate: " . $exchangeClient->errorMessage);
            return;
        }

        // Insert vào DB
        $this->db->table('exchange_rates')->insert([
            'currency_from'     => 'USD',
            'currency_to'       => 'VND',
            'rate'              => $rate,
            'is_active'         => 1,
            'created_at'        => Time::now('Asia/Ho_Chi_Minh'),
        ]);

        CLI::write("Inserted rate USD/VND = $rate\n", 'green');
    }
}
