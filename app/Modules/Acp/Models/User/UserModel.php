<?php namespace Modules\Acp\Models\User;

use CodeIgniter\Model;
use Modules\Acp\Entities\User;
use Modules\Acp\Models\Traits\recoverItem;

class UserModel extends Model
{
    use recoverItem;
    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $returnType = User::class;
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'username', 'avatar', 'password_hash', 'email', 'gid', 'root_user',
        'status', 'status_message', 'reset_hash', 'reset_at', 'reset_expires', 'activate_hash', 'active', 'active_at', 'force_pass_reset','user_type'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules = [
        'email'         => 'permit_empty|valid_email|is_unique[users.email,id,{id}]',
        'username'      => 'required|alpha_numeric_space|min_length[3]|is_unique[users.username,id,{id}]',
        'password_hash' => 'required|min_length[4]',
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;

    /**
     * Logs a password reset attempt for posterity sake.
     *
     * @param string      $email
     * @param string|null $token
     * @param string|null $ipAddress
     * @param string|null $userAgent
     * @param string|null $userType
     * @param array|null $userData - array of user data ( user ID + old_password )
     */
    public function logResetAttempt(string $email, string $token = null, string $ipAddress = null, string $userAgent = null, $userType = null, $userData = null)
    {
        $insertData = [
            'email' => $email,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'token' => $token,
            'user_type' => $userType,
            'created_at' => date('Y-m-d H:i:s')
        ];
        if ( !empty($userData) && is_array($userData) ) {
            $insertData['user_id'] = $userData['user_id'] ?? null;
            $insertData['old_password'] = $userData['old_password'] ?? null;
        }
        $this->db->table('auth_reset_attempts')->insert($insertData);
    }

    /**
     * Logs an activation attempt for posterity sake.
     *
     * @param string|null $token
     * @param string|null $ipAddress
     * @param string|null $userAgent
     */
    public function logActivationAttempt(string $token = null, string $ipAddress = null, string $userAgent = null)
    {
        $this->db->table('auth_activation_attempts')->insert([
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'token' => $token,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

}
