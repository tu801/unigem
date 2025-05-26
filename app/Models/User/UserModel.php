<?php

declare(strict_types=1);

namespace App\Models\User;

use App\Entities\User;
use CodeIgniter\Shield\Models\UserModel as ShieldUserModel;

class UserModel extends ShieldUserModel
{
    protected $returnType     = User::class;

    protected function initialize(): void
    {
        parent::initialize();

        $this->allowedFields = [
            ...$this->allowedFields,
            'avatar',
            'user_type'
        ];
    }
}