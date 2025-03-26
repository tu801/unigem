<?php
namespace Modules\Acp\Models;

use CodeIgniter\Model;

class RecordTypeModel extends Model
{
    protected $table = 'record_type';
    protected $primaryKey = 'id';

    protected $returnType = 'object';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'name', 'developer_name', 'object_type'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = '';

    protected $skipValidation = true;

    public function insertOrUpdate($data)
    {
        if(isset($data['developer_name']) && isset($data['object_type'])) {
            $buider = $this->db->table($this->table);

            $item = $buider->where('developer_name', $data['developer_name'])->get()->getFirstRow();

            if (isset($item->id)) {
                    $buider->set([
                        'object_type' => $data['object_type']
                    ])
                    ->where('id', $item->id)
                    ->update();
                return $buider->where('id', $item->id)->get()->getFirstRow();

            } else {
                $insertData = [
                    'name'           => $data['name'] ?? '',
                    'developer_name' => $data['developer_name'],
                    'object_type'    => $data['object_type'],
                ];
                $rsId       = $this->insert($insertData);
                return $buider->where('id', $rsId)->get()->getFirstRow();
            }
        }
    }
}