<?php
namespace App\Models;

use CodeIgniter\Model;

class PermissionModel extends Model
{
    protected $table = 'permissions';
    protected $primaryKey = 'id';

    protected $returnType = 'object';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'name', 'group', 'description', 'action',
    ];

    protected $useTimestamps = false;

    protected $skipValidation = true;

    /**
     * Save perrmission for selected user
     * @param int $user_id
     * @param array|null $permissions
     * @return array $permissions
     */
    public function saveUserPermissions(int $user_id = 0, array $permissions = null) :array
    {
        $tblPrefix = $this->db->getPrefix();

        $builder = $this->db->table($tblPrefix.'users_permissions');
        //unset old per
        $builder->where('user_id', $user_id)->delete();

        //set new per
        foreach ($permissions as $item){
            $per = $builder->where('user_id', $user_id)->where('permission_id', $item)->get()->getFirstRow();

            if ( empty($per) ) $builder->insert(['user_id' => $user_id, 'permission_id' => $item]);
        }
        $perData = $builder->select('permission_id')->where('user_id', $user_id)->get()->getResultArray();
        $returnData = [];
        foreach ($perData as $per) array_push($returnData, $per['permission_id']);

        //clear cache
        cache()->delete("{$user_id}_permissions");

        return $returnData;
    }

    /**
     * Gets all permissions for a user in a way that can be
     * easily used to check against:
     *
     * [
     *  id => name,
     *  id => name
     * ]
     *
     * @param int $userId
     *
     * @return array
     */
    public function getPermissionsForUser(int $userId): array
    {
        $tblPrefix = $this->db->getPrefix();
        if (! $found = cache("{$userId}_permissions"))
        {
            $fromUser = $this->db->table($tblPrefix.'users_permissions')
                ->select('id, permissions.name, permissions.group, permissions.action')
                ->join($tblPrefix.'permissions', $tblPrefix.'permissions.id = permission_id', 'inner')
                ->where('user_id', $userId)
                ->get()
                ->getResultObject();
//            $fromGroup = $this->db->table('auth_groups_users')
//                ->select('auth_permissions.id, auth_permissions.name')
//                ->join('auth_groups_permissions', 'auth_groups_permissions.group_id = auth_groups_users.group_id', 'inner')
//                ->join('auth_permissions', 'auth_permissions.id = auth_groups_permissions.permission_id', 'inner')
//                ->where('user_id', $userId)
//                ->get()
//                ->getResultObject();

//            $combined = array_merge($fromUser, $fromGroup);

            $found = [];
            foreach ($fromUser as $row)
            {
                $found[$row->id] = strtolower($row->group.'/'.$row->name.'/'.$row->action);
            }

            cache()->save("{$userId}_permissions", $found, 300);
        }

        return $found;
    }

}