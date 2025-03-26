<?php namespace Modules\Acp\Models\User;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;

class UsermetaModel extends Model
{
    protected $table = 'usermeta';
    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'user_id', 'meta_key', 'meta_value',
    ];

    protected $skipValidation = true;

    public function __construct(ConnectionInterface &$db = null, ValidationInterface $validation = null)
    {
        parent::__construct($db, $validation);
    }

    public function getMeta(&$user, $metaType = 'usermeta'){
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
