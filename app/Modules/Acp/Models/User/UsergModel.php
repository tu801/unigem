<?php namespace Modules\Acp\Models\User;

use CodeIgniter\Model;

class UsergModel extends Model
{
    protected $table = 'userg';
    protected $primaryKey = 'id';

    protected $returnType = 'object';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'name', 'description', 'permissions',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $skipValidation = true;

    public function getGroupData(int $groupId) {
        $builder = $this->db->table($this->table);
        //get group info
        return $builder->select('id, name, description, permissions')
                ->where('id', $groupId)
                ->get()->getFirstRow('array');
    }

    /**
     * create record item if not exist otherwise update the item
     * @param $input
     */
    public function insertOrUpdate($input) {
        $item = $this->where('name', $input['name'])
                ->get()->getFirstRow();
        if ( !empty($item) || isset($item->id) ) {
            $this->update($item->id, $input);
        } else {
            $this->insert($input);
        }
    }
}
