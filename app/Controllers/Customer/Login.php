<?php
namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Enums\UserTypeEnum;
use App\Models\Store\Customer\CustomerModel;
use App\Traits\Customer\CustomerValidationRules;
use App\Traits\SpamFilter;

/**
 * Class Login
 * Handles customer login functionality.
 */
class Login extends BaseController
{
    use CustomerValidationRules, SpamFilter;

    public function __construct()
    {
        parent::__construct();
        $this->_model = model(CustomerModel::class);
    }

    public function loginView()
    {
        $this->page_title = lang('Auth.login');
        if (auth()->loggedIn()) {
            return redirect()->to('/');
        }

        return $this->_render('customer/auth/login', $this->_data);
    }

    public function loginSubmit()
    {
        $this->checkSpam();

        $this->page_title = lang('Auth.login');
        if (auth()->loggedIn()) {
            return redirect()->to('/');
        }

        $rules = $this->getLoginValidationRules();

        if (! $this->validateData($this->request->getPost(), $rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        /** @var array $credentials */
        $credentials             = $this->request->getPost(setting('Auth.validFields')) ?? [];
        $credentials             = array_filter($credentials);
        $credentials['password'] = $this->request->getPost('password');
        $remember                = (bool) $this->request->getPost('remember');

        /** @var Session $authenticator */
        $authenticator = auth('session')->getAuthenticator();

        try {
            // Attempt to login
            $result = $authenticator->remember($remember)->attempt($credentials);
            if (! $result->isOK()) {
                return redirect()->back()->withInput()->with('errors', $result->reason());
            }

            $user = $authenticator->getUser(); 
            
            if ($user === null) {
                return redirect()->back()->withInput()->with('errors', lang('Auth.invalidLogin'));
            }
        } catch (\Exception $e) {
            $message = $authenticator->isPending() ? 
                lang('Auth.activationBlocked') :
                lang('Common.somethingWentWrong');

            return redirect()->back()->withInput()->with('errors', $message);
        }

        if ( $user->user_type == UserTypeEnum::ADMIN) {
            auth()->logout();
            return redirect()->back()->with('errors', lang('Auth.unknownPermission', [UserTypeEnum::ADMIN]));
        }
        return redirect()->route('cus_profile')->with('message', lang('Auth.loginSuccess'));
    }
}