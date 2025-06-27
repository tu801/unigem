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


    /**
     * Check if the customer is logged in, if not redirect to home page
     *
     * @return RedirectResponse|void
     */
    public function checkCustomerLoggedIn()
    {
        $authenticator = auth('session')->getAuthenticator();
        if (!auth()->loggedIn()) {
            return redirect()->route('cus_login')->with('message', lang('Customer.login_required'));
        }

        $user = $authenticator->getUser();
        if ($user->user_type != UserTypeEnum::CUSTOMER) {
            return redirect()->route('/');
        }

        $customer = model(CustomerModel::class)->queryCustomerByUserId($user->id)->first();

        $this->user = $user;
        $this->_data['customer'] = $customer;
    }
}