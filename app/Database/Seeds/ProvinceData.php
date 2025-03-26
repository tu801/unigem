<?php

namespace App\Database\Seeds;

use CodeIgniter\CLI\CLI;
use CodeIgniter\Database\Seeder;

class ProvinceData extends Seeder
{
    public function run()
    {
        // seed province data
        $this->__seedProvince();

        // seed district data
        $this->__seedDistrict();

        // seed ward data
        $this->__seedWard();
        
    }

    private function __seedProvince()
    {
        $path = APPPATH."Database/Seeds/RawData/provinces.json"; 

        $data = file_get_contents($path);
        $data = json_decode($data);

        $builder = $this->db->table('provinces');
        foreach ($data as $item) {
            $check = $builder->where('id', $item->id)->get()->getFirstRow();

            if ( !isset($check->id) ) {
                //insert data
                $insertData = [
                    'id'            => $item->id, 
                    'code'          => $item->code, 
                    'name'          => $item->name, 
                    'name_en'       => $item->name_en, 
                    'full_name'     => $item->full_name, 
                    'full_name_en'  => $item->full_name_en, 
                    'unit_name'     => $item->unit_name, 
                    'unit_name_en'  => $item->unit_name_en
                ];
                $builder->insert($insertData);
                $id = $this->db->insertID();

                CLI::newLine();
                CLI::write("- Đã thêm thành công province #{$id} : {$item->full_name}");
            }
            
        }
        CLI::newLine();
        CLI::write('=== Seeding Province Done ===');
    }

    private function __seedDistrict()
    {
        $path = APPPATH."Database/Seeds/RawData/districts.json"; 

        $data = file_get_contents($path);
        $data = json_decode($data);
        $builder = $this->db->table('districts');

        foreach ($data as $item) {
            //insert defaut group
            $insertData = [
                'id'            => $item->id, 
                'province_id'   => $item->province_id, 
                'code'          => $item->code, 
                'name'          => $item->name, 
                'name_en'       => $item->name_en, 
                'full_name'     => $item->full_name, 
                'full_name_en'  => $item->full_name_en, 
                'unit_name'     => $item->unit_name, 
                'unit_name_en'  => $item->unit_name_en
            ];
            $builder->insert($insertData);
            $id = $this->db->insertID();
            
            CLI::newLine();
            CLI::write("- Đã thêm thành công district #{$id} : {$item->full_name}");
        }
        CLI::newLine();
        CLI::write('=== Seeding District Done ===');
    }

    private function __seedWard()
    {
        $path = APPPATH."Database/Seeds/RawData/wards.json"; 

        $data = file_get_contents($path);
        $data = json_decode($data);
        $builder = $this->db->table('wards');

        foreach ($data as $item) {
            //insert defaut group
            $insertData = [
                'id'            => $item->id, 
                'district_id'   => $item->district_id, 
                'code'          => $item->code, 
                'name'          => $item->name, 
                'name_en'       => $item->name_en, 
                'full_name'     => $item->full_name, 
                'full_name_en'  => $item->full_name_en, 
                'unit_name'     => $item->unit_name, 
                'unit_name_en'  => $item->unit_name_en
            ];
            $builder->insert($insertData);
            $id = $this->db->insertID();
            
            CLI::newLine();
            CLI::write("- Đã thêm thành công ward #{$id} : {$item->full_name}");
        }
        CLI::newLine();
        CLI::write('=== Seeding Wards Done ===');
    }
}
