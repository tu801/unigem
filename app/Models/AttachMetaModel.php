<?php
namespace App\Models;

use App\Enums\Store\Product\ProductAttachMetaEnum;
use CodeIgniter\I18n\Time;
use CodeIgniter\Model;

class AttachMetaModel extends Model
{
    protected $table = 'attach_meta';
    protected $primaryKey = 'id';

    protected $returnType = 'object';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'mod_name', 'mod_id', 'mod_type', 'images', 'lang'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $skipValidation = true;

    public function getAttMeta(string $mod_id, string $mod_name) {
        if ( !isset($mod_id) || !isset($mod_name) ) return false;

        $builder = $this->db->table($this->table);
        $data = $builder->getWhere(['mod_id' => $mod_id, 'mod_name' => $mod_name])->getFirstRow();
        if ( empty($data) ) return;
        if ( $data->mod_type === 'single' ) {
            $images = json_decode($data->images);
            if ( !empty($images) ) $data->images = $images[0];
            else $data->images = $images;
        }
        else if ( $data->mod_type === 'gallery' ) {
            $data->images = json_decode($data->images);
        }
        return $data;
    }

    public function updateMeta($inputData, $id) {
        if ( !isset($inputData['att_meta_type']) || !isset($inputData['att_meta_mod_name'])
            || !isset($inputData['att_meta_mod_id']) || !isset($inputData['att_meta_img'])) return false;
        
        $_attach = new AttachModel();

        $imagesFiles = explode(';', $inputData['att_meta_img']);
        $mtVal = [];
        foreach ($imagesFiles as $imgId) {
            $file =  $_attach->select('id, file_name, file_title, created_at')
                    ->getWhere(['id' => $imgId])->getFirstRow();
            if ( isset($file->id) )  {
                $myTime = Time::parse($file->created_at);
                $file->full_image = 'uploads/attach/'.$myTime->format( 'Y/m')."/{$file->file_name}";
                $file->thumb_image = 'uploads/attach/'.$myTime->format( 'Y/m')."/thumb/{$file->file_name}";

                // for product images mod
                if ( $inputData['att_meta_mod_name'] == ProductAttachMetaEnum::MODE_NAME ) {
                    $file->product_thumb = create_product_thumb($file);
                }

                $mtVal[] = $file;
            }
        }

        //update attach meta
        $metaModel = $this->db->table($this->table);
        $metaData = [
            'images' => json_encode($mtVal)
        ];
        $metaModel->where('id', $id);
        $metaModel->update($metaData);
        return true;

    }

    public function saveAttachFiles($inputData) {
        if ( !isset($inputData['att_meta_type']) || !isset($inputData['att_meta_mod_name'])
            || !isset($inputData['att_meta_mod_id']) || !isset($inputData['att_meta_img'])) return false;
        $tblPrefix = $this->db->getPrefix();
        $_attach = new AttachModel();

        $imagesFiles = explode(';', $inputData['att_meta_img']);
        $mtVal = [];
        foreach ($imagesFiles as $imgId) {
            $file =  $_attach->select('id, file_name, file_title, created_at')
                ->getWhere(['id' => $imgId])->getFirstRow();
            if ( isset($file->id) )  {
                $myTime = Time::parse($file->created_at);
                $file->full_image = 'uploads/attach/'.$myTime->format( 'Y/m')."/{$file->file_name}";
                $file->thumb_image = 'uploads/attach/'.$myTime->format( 'Y/m')."/thumb/{$file->file_name}";

                // for product images mod
                if ( $inputData['att_meta_mod_name'] == ProductAttachMetaEnum::MODE_NAME ) {
                    $file->product_thumb = create_product_thumb($file);
                }

                $mtVal[] = $file;
            }
        }

        //insert into attach meta
        $metaModel = $this->db->table($this->table);
        $metaData = [
            'mod_name' => $inputData['att_meta_mod_name'],
            'mod_id' => $inputData['att_meta_mod_id'],
            'mod_type' => $inputData['att_meta_type'],
            'images' => json_encode($mtVal),
            'created_at' => Time::now()
        ];
        //check data first
        $check = $metaModel->where('mod_name', $inputData['att_meta_mod_name'])
                ->where('mod_id', $inputData['att_meta_mod_id'])->get()->getFirstRow();
        if ( isset($check->id) && $check->id > 0 ) {
            $metaData = [
                'images' => json_encode($mtVal),
                'updated_at' => Time::now()
            ];
            $metaModel->update($metaData, $check->id);
        } else $metaModel->insert($metaData);

        return true;
    }

    public function insertOrUpdate($input) {
        $buider = $this->db->table($this->table);

        $id       = $input['id'] ?? null; // id attach meta
        $mod_name = $input['mod_name'] ?? null;
        $mod_id   = $input['mod_id'] ?? null;
        $mod_type = $input['mod_type'] ?? null;
        $val      = $input['value'] ?? null;

        if (isset($id)) {
            $buider->set('images', $val)
                ->set('updated_at', date('Y-m-d H:i:s'))
                ->where('id', $id)
                ->update();
            return $buider->where('id', $id)->get()->getFirstRow();
        }else {
            $checkMeta = $buider->where('mod_name', $mod_name)
                ->where('mod_id', $mod_id)
                ->get()->getFirstRow();

            if (isset($checkMeta->id)) {
                $buider->set('images', $val)
                    ->set('updated_at', date('Y-m-d H:i:s'))
                    ->where('id', $checkMeta->id)
                    ->update();
                return $buider->where('id', $checkMeta->id)->get()->getFirstRow();
            }
            
            $insertData = [
                'user_init'  => user_id(),
                'mod_name'   => $mod_name,
                'mod_id'     => $mod_id,
                'mod_type'   => $mod_type,
                'images'     => $val,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $rsId = $this->insert($insertData);
            return $buider->where('id', $rsId)->get()->getFirstRow();
        }
    }

    /**
     * delete attach meta record
     * @param $id Attach meta id
     * @return void
     */
    public function deleteMeta($id) {
        $builder = $this->db->table($this->table);
        $builder->where('id', $id)->delete();
    }
}
