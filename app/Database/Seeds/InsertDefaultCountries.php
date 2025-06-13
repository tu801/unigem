<?php

namespace App\Database\Seeds;

use CodeIgniter\CLI\CLI;
use CodeIgniter\Database\Seeder;

class InsertDefaultCountries extends Seeder
{
    public function run()
    {
        // API endpoint with fields specified
        $url = 'https://restcountries.com/v3.1/all?fields=cca2,name,flags,currencies';

        // Retrieve country data from API
        $response = file_get_contents($url);
        $countries = json_decode($response, true);

        foreach ($countries as $item) {
            $code = $item['cca2']; // 2-letter ISO code
            $name = $item['name']['common']; // Common name
            $full_name = $item['name']['official']; // Official name
            $flags = json_encode($item['flags'], JSON_UNESCAPED_UNICODE); // Flags url
            $currencyJson = json_encode($item['currencies'], JSON_UNESCAPED_UNICODE);

            // Check if country already exists by code and name
            $existing = $this->db->table('countries')
                ->getWhere(['code' => $code, 'name' => $name])
                ->getRow();

            if ($existing) {
                continue;
            }

            // Prepare data
            $data = [
                'code' => $code,
                'name' => $name,
                'full_name' => $full_name,
                'flags' => $flags,
                'currency' => $currencyJson,
                'created_at' => date('Y-m-d H:i:s'),
            ];

            $this->db->table('countries')->insert($data);
            CLI::write('Country ' . $name . ' inserted', 'green');
        }
        CLI::write('=== All countries inserted ===', 'green');
    }
}