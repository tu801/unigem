<?php

namespace App\Database\Seeds;

use CodeIgniter\CLI\CLI;
use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class ExchangeRateSeed extends Seeder
{
    public function run()
    {
        $client = \Config\Services::curlrequest();

        // API dùng open.er-api.com
        $response = $client->get('https://open.er-api.com/v6/latest/USD');

        if ($response->getStatusCode() !== 200) {
            echo "Failed to fetch exchange rate.\n";
            return;
        }

        $data = json_decode($response->getBody(), true);
        $rate = $data['rates']['VND'] ?? null;

        if (!$rate) {
            echo "VND rate not found in API response.\n";
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
