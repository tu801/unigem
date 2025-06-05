<?php

namespace App\Controllers\Customer;

class AuthCustomer extends \App\Controllers\BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function logout()
    {
        auth()->logout();

        return redirect()->to('/')->with('message', lang('Auth.successLogout'));;
    }
}