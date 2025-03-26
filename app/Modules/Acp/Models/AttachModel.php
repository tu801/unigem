<?php
namespace Modules\Acp\Models;

use CodeIgniter\Model;

class AttachModel extends Model
{
    protected $table = 'attach';
    protected $primaryKey = 'id';

    protected $returnType = 'object';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'user_id', 'user_type', 'file_name', 'file_title'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $skipValidation = true;

    public function setModAtt($conds){
        $sql = "UPDATE tmt_{$this->table} SET mod_id = '{$conds['mod_id']}' WHERE user_id = '{$conds['user_id']}' AND mod_name = '{$conds['mod_name']}' AND mod_id = 0";
        $this->db->query($sql);
    }

    public function convertValue(&$data){
        $date = date_create($data->created_at);
        $img_url = 'uploads/attach/'.date_format($date, 'Y/m');

        $data->full_image = $img_url."/{$data->file_name}";
        $data->thumb_image = $img_url."/thumb/{$data->file_name}";
    }

    public function getAttFile($id) {
        $img = $this->db->table($this->table)
            ->select(['id', 'file_name', 'file_title', 'created_at'])
            ->where('id', $id)
            ->get()->getFirstRow();

        if ( !empty($img) ) {
            $this->convertValue($img);
            return $img;
        } else return false;
    }
}