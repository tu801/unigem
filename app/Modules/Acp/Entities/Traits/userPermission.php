<?php
/**
 * @author tmtuan
 * created Date: 8/19/2021
 * project: genesolution
 */

namespace Modules\Acp\Entities\Traits;


use App\Models\PermissionModel;



trait userPermission {
    /**
     * Determines whether the user has the appropriate permission,
     * either directly, or through one of it's groups.
     *
     * @param string $permission
     *
     * @return bool
     */
    public function can(string $permission)
    {
        if ( $permission == 'root' ) {
            if ( $this->attributes['root_user'] == 1 ) return true;
            else return false;
        } else {
            if ( $this->attributes['root_user'] == 1 ) return true;
            else return in_array(strtolower($permission), $this->getPermissions());
        }
    }

    /**
     * Returns the user's permissions, formatted for simple checking:
     *
     * [
     *    id => name,
     *    id=> name,
     * ]
     *
     * @return array|mixed
     */
    public function getPermissions()
    {
        if (empty($this->id))
        {
            throw new \RuntimeException('Users must be created before getting permissions.');
        }

        if (empty($this->permissions))
        {
            $this->permissions = (new PermissionModel())->getPermissionsForUser($this->id);
        }

        return $this->permissions;
    }

    /**
     * Save permissions for user
     *
     * @param array $permissions
     *
     * @return $this
     */
    public function setPermissions(array $permissions = null)
    {
        if ( empty($permissions) || !is_array($permissions) ) throw new \RuntimeException('Invalid permission data');
        $this->permissions = (new PermissionModel())->saveUserPermissions($this->id, $permissions);

        return $this;
    }

    /**
     * Checks to see if a user is root.
     *
     * @return bool
     */
    public function getIsRoot(): bool
    {
        $this->is_root = (isset($this->attributes['root_user']) && $this->attributes['root_user'] == 1) ? true : false;
        return $this->is_root;
    }
}