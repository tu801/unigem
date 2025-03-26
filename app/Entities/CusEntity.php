<?php
/**
 * Author: tmtuan
 * Created date: 11/11/2023
 * Project: Unigem
 **/

namespace App\Entities;


use Modules\Acp\Entities\Store\Customer\Customer;
use Modules\Acp\Models\User\UserModel;

class CusEntity extends Customer
{
    private $gravatar = [
        'identicon', 'monsterid', 'wavatar', 'retro', 'robohash'
    ];

    protected $avatar;

    public function getAvatar()
    {
        if (empty($this->id)) {
            throw new \RuntimeException(lang('Acp.invalid_entity'));
        }

        $config = config('Site');
        $avatarUrl = '';

        if ( !isset($this->attributes['user_id']) || empty($this->attributes['user_id']) ) {
            $this->avatar = $this->avatarLink();
        } else {
            $user = model(UserModel::class)
                ->find($this->attributes['user_id']);

            $date = new \DateTime($user->created_at);
            $this->avatar = base_url("uploads/" . $date->format('Y') . '/' . $user->avatar);
            $avtReal = $config->uploadFolder . $date->format('Y') . '/' . $user->avatar;
            if (!file_exists($avtReal)) {
                $this->avatar = $this->avatarLink();
            }
        }

        return $this->avatar;
    }

    public function avatarLink(?int $size = null): string
    {
        $hash = md5(strtolower(trim($this->cus_email)));
        $gavatarKey = array_rand($this->gravatar);

        return "https://www.gravatar.com/avatar/{$hash}?" . http_build_query([
                's' => ($size ?? 60),
                'd' => $this->gravatar[$gavatarKey],
            ]);
    }
}