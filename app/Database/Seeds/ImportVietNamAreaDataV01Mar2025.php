<?php

namespace App\Database\Seeds;

use CodeIgniter\CLI\CLI;
use CodeIgniter\Database\Seeder;
use Exception;
use CodeIgniter\Database\BaseConnection;

/**
 * Update Vietnamese area data base on Issued Decree 1365/NQ-UBTVQH15
 * Vietnamese Provinces Database Dataset export Date Sat, 01 Mar 2025 21:28:48 +0700 
 */
class ImportVietNamAreaDataV01Mar2025 extends Seeder
{
    protected $provincesFile = 'app/Database/Seeds/RawData/v01Mar2025/provinces.json';
    protected $districtsFile = 'app/Database/Seeds/RawData/v01Mar2025/districts.json';
    protected $wardsFile     = 'app/Database/Seeds/RawData/v01Mar2025/wards.json';

    public function run()
    {
        $db = \Config\Database::connect();
        $db->transStart();
        try {
            $this->seedProvinces($db);
            $this->seedDistricts($db);
            $this->seedWards($db);
            $db->transComplete();
            if ($db->transStatus() === false) {
                throw new Exception('Transaction failed.');
            }
            CLI::write("Seeder completed successfully.", 'green');
        } catch (Exception $e) {
            $db->transRollback();
            CLI::write("Error: " . $e->getMessage(), 'red');
        }
    }

    protected function seedProvinces(BaseConnection $db)
    {
        $json = file_get_contents(ROOTPATH . $this->provincesFile);
        $newData = json_decode($json, true);
        $newMap = [];
        foreach ($newData as $item) {
            $newMap[$item['id'] . '_' . $item['code']] = $item;
        }
        $oldRows = $db->table('provinces')->get()->getResultArray();
        $oldMap = [];
        foreach ($oldRows as $row) {
            $oldMap[$row['id'] . '_' . $row['code']] = $row;
        }
        // Soft delete provinces not exist in new data
        $toDelete = array_diff(array_keys($oldMap), array_keys($newMap));
        if ($toDelete) {
            $db->table('provinces')->whereIn(
                "CONCAT(id, '_', code)",
                $toDelete
            )->update(['deleted_at' => date('Y-m-d H:i:s')]);
            CLI::write("Soft deleted " . count($toDelete) . " provinces.", 'yellow');
        }
        // Update or insert
        foreach ($newMap as $key => $item) {
            if (isset($oldMap[$key])) {
                $diff = array_diff_assoc($item, $oldMap[$key]);
                unset($diff['id'], $diff['code']);
                if ($diff) {
                    $item['updated_at'] = date('Y-m-d H:i:s');
                    $db->table('provinces')->where(['id' => $item['id'], 'code' => $item['code']])->update($item);
                    CLI::write("Updated province: {$item['name']}", 'green');
                }
            } else {
                $db->table('provinces')->insert($item);
                CLI::write("Inserted province: {$item['name']}", 'green');
            }
        }
    }

    protected function seedDistricts(BaseConnection $db)
    {
        $json = file_get_contents(ROOTPATH . $this->districtsFile);
        $newData = json_decode($json, true);
        // Tạo map theo province_id và code
        $newMap = [];
        foreach ($newData as $item) {
            $newMap[$item['province_id'] . '_' . $item['code']] = $item;
        }
        $oldRows = $db->table('districts')->get()->getResultArray();
        $oldMap = [];
        foreach ($oldRows as $row) {
            $oldMap[$row['province_id'] . '_' . $row['code']] = $row;
        }
        // Soft delete districts không còn trong file mới
        $toDelete = array_diff(array_keys($oldMap), array_keys($newMap));
        if ($toDelete) {
            $db->table('districts')->whereIn(
                "CONCAT(province_id, '_', code)",
                $toDelete
            )->update(['deleted_at' => date('Y-m-d H:i:s')]);
            CLI::write("Soft deleted " . count($toDelete) . " districts.", 'yellow');
        }

        // Update or insert
        foreach ($newMap as $key => $item) {
            if (isset($oldMap[$key])) {
                $diff = array_diff_assoc($item, $oldMap[$key]);
                unset($diff['province_id'], $diff['code']);
                if ($diff) {
                    $item['updated_at'] = date('Y-m-d H:i:s');
                    $db->table('districts')->where(['province_id' => $item['province_id'], 'code' => $item['code']])->update($item);
                    CLI::write("Updated district: {$item['name']}", 'green');
                }
            } else {
                $db->table('districts')->insert($item);
                CLI::write("Inserted district: {$item['name']}", 'green');
            }
        }
    }

    protected function seedWards(BaseConnection $db)
    {
        $json = file_get_contents(ROOTPATH . $this->wardsFile);
        $allWards = json_decode($json, true);
        // Chia nhỏ theo district_id
        $wardsByDistrict = [];
        foreach ($allWards as $ward) {
            $wardsByDistrict[$ward['district_id']][] = $ward;
        }
        foreach ($wardsByDistrict as $districtId => $wards) {
            $newMap = [];
            foreach ($wards as $item) {
                $newMap[$item['district_id'] . '_' . $item['code']] = $item;
            }
            $oldRows = $db->table('wards')->where('district_id', $districtId)->get()->getResultArray();
            $oldMap = [];
            foreach ($oldRows as $row) {
                $oldMap[$row['district_id'] . '_' . $row['code']] = $row;
            }
            // Soft delete wards không còn trong file mới
            $toDelete = array_diff(array_keys($oldMap), array_keys($newMap));
            if ($toDelete) {
                $db->table('wards')->where('district_id', $districtId)
                    ->whereIn("CONCAT(district_id, '_', code)", $toDelete)
                    ->update(['deleted_at' => date('Y-m-d H:i:s')]);
                CLI::write("Soft deleted " . count($toDelete) . " wards in district $districtId.", 'yellow');
            }

            // Update hoặc insert
            foreach ($newMap as $key => $item) {
                if (isset($oldMap[$key])) {
                    $diff = array_diff_assoc($item, $oldMap[$key]);
                    unset($diff['district_id'], $diff['code']);
                    if ($diff) {
                        $item['updated_at'] = date('Y-m-d H:i:s');
                        $db->table('wards')->where(['district_id' => $item['district_id'], 'code' => $item['code']])->update($item);
                        CLI::write("Updated ward: {$item['name']}", 'green');
                    }
                } else {
                    $db->table('wards')->insert($item);
                    CLI::write("Inserted ward: {$item['name']}", 'green');
                }
            }
        }
    }
}