<?php namespace Modules\Acp\Entities;

use CodeIgniter\Entity\Entity;
use Modules\Acp\Entities\Traits\userActivation;
use Modules\Acp\Entities\Traits\userPassword;
use Modules\Acp\Entities\Traits\userPermission;
use Modules\Acp\Models\User\UsergModel;
use Modules\Acp\Models\User\UsermetaModel;
use Modules\Acp\Models\User\UserModel;

class User extends Entity
{
    use userPassword;
    use userPermission;
    use userActivation;

    /**
     * Maps names used in sets and gets against unique
     * names within the class, allowing independence from
     * database column names.
     *
     * Example:
     *  $datamap = [
     *      'db_name' => 'class_name'
     *  ];
     */

    protected $datamap = [];

    /**
     * Define properties that are automatically converted to Time instances.
     */
    protected $dates = ['reset_at', 'reset_expires', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * Array of field names and the type of value to cast them as
     * when they are accessed.
     */
    protected $casts = [
        'active'           => 'boolean',
        'force_pass_reset' => 'boolean',
    ];

    /**
     * Per-user permissions cache
     * @var array
     */
    protected $permissions = [];

    /**
     * @var string
     */
    protected $model_class;

    /**
     * User Group Information
     * @var array
     */
    protected $groupData = [];

    /**
     * Per-user meta cache
     * @var array
     */
    protected $meta = [];

    /**
     * User avatar
     * @var string
     */
    protected $img_avatar = '';

    /**
     * check user is root
     * @var string
     */
    protected bool $is_root = false;


    /**
     * Get model class name
     */
    public function getModelClass()
    {
        return $this->model_class = UserModel::class;
    }

    /**
     * get current user Meta data
     *
     * @return array|bool
     */
	public function getMeta() {
        if (empty($this->id))
        {
            throw new \RuntimeException('Users must be created before getting meta data.');
        }

        if ( empty($this->meta) ) {
            $metaModel = new UsermetaModel();
            $config = config('Acp');
            if ( !empty($config->user_meta) ) {
                $metaData = [];
                foreach ($config->user_meta as $metaKey=>$val) {
                    $result = $metaModel->where('user_id', $this->attributes['id'])->where('meta_key', $metaKey)->get()->getFirstRow('array');
                    if ( isset($result) ) $metaData[$metaKey] = $result['meta_value'];
                }
            }
            $this->meta = $metaData;
        }

        return $this->meta;
    }

    /**
     * get user group information
     * @return array|mixed
     */
    public function getGroupData() {
        if (empty($this->id))
        {
            throw new \RuntimeException('Users must be created before getting meta data.');
        }

        if ( empty($this->groupData) ) {
            $this->groupData = (new UsergModel())->getGroupData($this->attributes['gid']);
        }
        return $this->groupData;
    }

    public function getImgAvatar() {
        if (empty($this->id)) {
            throw new \RuntimeException('Users must be created before getting meta data.');
        }
        $config = config('Acp');
        $avtImg = '';

        if ( !empty($this->attributes['avatar']) ) {
            $date = new \DateTime($this->attributes['created_at']);
            $avtImg = "uploads/".$date->format('Y').'/'.$this->attributes['avatar'];
            $avtReal = $config->uploadPath.$date->format('Y').'/'.$this->attributes['avatar'];
            if (!file_exists($avtReal)) {
                $avtImg = $config->templatePath.'assets/img/avatar.png';
            }
        } else {
            $avtImg = "{$config->templatePath}assets/img/avatar.png";
        }
        $this->img_avatar = base_url($avtImg);
        return $this->img_avatar;
    }

    public function displayBirthday($format = 'd/m/Y')
    {
        $date = new \DateTime($this->attributes['birthday']);

        return $date->format($format);
    }

}
