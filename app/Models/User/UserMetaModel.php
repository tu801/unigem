<?php 
namespace App\Models\User;

use CodeIgniter\Model;

class UserMetaModel extends Model
{
    protected $table = 'user_meta';
    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'user_id', 'meta_key', 'meta_value',
    ];

    protected $skipValidation = true;

    public function getMeta(&$user, $metaType = 'user_meta'){
        $config = config('Acp');
        $builder = $this->db->table($this->table);
        if ( !empty($config->user_meta) ) {
            foreach ($config->user_meta as $metaKey=>$val) {
                $builder->where('user_id', $user->id);
                $builder->where('meta_key', $metaKey);
                $result = $builder->get($this->_table)->getFirstRow('array');
                $user->meta["{$metaKey}"] = $result['meta_value'];
            }
        }
    }
}
