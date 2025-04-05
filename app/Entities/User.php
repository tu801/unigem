<?php

/**
 * Author: tmtuan
 * Created date: 05/04/2025
 **/

namespace App\Entities;

use App\Models\User\UserMetaModel;
use CodeIgniter\Shield\Entities\User as ShieldUser;

class User extends ShieldUser
{

    /**
     * Per-user meta cache
     * @var array
     */
    protected $meta = [];

    /**
     * get current user Meta data
     *
     * @return array|bool
     */
    public function getMeta()
    {
        if (empty($this->id)) {
            throw new \RuntimeException('Users must be created before getting meta data.');
        }

        if (empty($this->meta)) {
            $metaModel = new UserMetaModel();
            $config = config('Acp');

            if (!empty($config->user_meta)) {
                $metaData = [];
                foreach ($config->user_meta as $metaKey => $val) {
                    $result = $metaModel->where('user_id', $this->attributes['id'])->where('meta_key', $metaKey)->get()->getFirstRow('array');
                    if (isset($result)) $metaData[$metaKey] = $result['meta_value'];
                }
            }
            $this->meta = $metaData;
        }

        return $this->meta;
    }
}