<?php

namespace App\Controllers\Customer;

use App\Enums\UserTypeEnum;
use App\Models\Country;
use App\Models\User\UserModel;
use App\Models\Store\Customer\CustomerModel;
use App\Traits\SpamFilter;

class CustomerController extends \App\Controllers\BaseController
{
    use SpamFilter;

    /** @var UserModel */
    protected $userModel;

    /** @var Country */
    protected $countryModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = model(UserModel::class);
        $this->_model  = model(CustomerModel::class);
        $this->countryModel = model(Country::class);

        // check customer logged in
        return $this->checkCustomerLoggedIn();
    }

    public function dashboard()
    {
        if (!auth()->loggedIn() || $this->user->user_type == UserTypeEnum::ADMIN) {
            return redirect()->route('/');
        }

        return $this->_render('customer/dashboard', $this->_data);
    }
}
