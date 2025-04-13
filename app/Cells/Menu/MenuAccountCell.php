<?php

namespace App\Cells\Menu;

use App\Models\CusModel;
use CodeIgniter\View\Cells\Cell;
use App\Enums\UserTypeEnum;

class MenuAccountCell extends Cell
{
    protected string $view = 'menu_account';
    public $cus_login;

    public function mount()
    {
        $user = user();
        if (isset($user->id) && $user->user_type == UserTypeEnum::CUSTOMER) {
            $this->cus_login = model(CusModel::class)->queryCustomerByUserId($user->id)->first();
        }
    }
}
