<?php
/**
 * @author tmtuan
 * created Date: 10/09/2021
 * project: fox_cms
 */

namespace Modules\Acp\Models\Blog;

use CodeIgniter\Model;

class MetaDataModel extends Model {
    protected $table = 'meta_data';
    protected $primaryKey = 'id';

    protected $returnType = 'object';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'lang_id', 'mod_name', 'mod_id', 'meta_key', 'meta_value'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = '';

    protected $skipValidation = true;

    /**
     * insert new meta data or edit if exist
     *
     * @param string $mod_name
     * @param int $mod_id
     * @param string $meta_key
     * @param $meta_val
     * @return mixed
     */
    public function createOrEdit(string $mod_name, int $mod_id, string $meta_key, $meta_val) {
        $buider = $this->db->table($this->table);

        $item = $buider->where('mod_name', $mod_name)
                ->where('mod_id', $mod_id)
                ->where('meta_key', $meta_key)
                ->get()->getFirstRow();

        if ( isset($item->id) ) {
            $buider->set('meta_value',json_encode($meta_val))
                ->set('updated_at', date('Y-m-d H:i:s'))
                ->where('id', $item->id)
                ->update();
            return $buider->where('id', $item->id)->get()->getFirstRow();

        } else {
            $insertData = [
                'user_init' => user_id(),
                'mod_name' => $mod_name,
                'mod_id' => $mod_id,
                'meta_key' => $meta_key,
                'meta_value' => json_encode($meta_val),
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ];
            $rsId = $this->insert($insertData);
            return $bd->where('id', $rsId)->get()->getFirstRow();
        }
    }
}