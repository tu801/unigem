<?php
/**
 * @author tmtuan
 * @github https://github.com/tu801
 * created Date: 11/6/2023
 */

namespace App\Controllers\Customer;


use App\Controllers\BaseController;
use App\Libraries\BreadCrumb\BreadCrumbCell;
use App\Models\CusModel;
use Modules\Acp\Enums\UserTypeEnum;
use Modules\Acp\Models\User\UserModel;
use Modules\Auth\Config\Services;
use Modules\Auth\Password;

class AuthCustomer extends BaseController
{
    protected $auth;
    protected $session;
    protected $authConfig;

    public function __construct()
    {
        parent::__construct();
        $this->session = Services::session();
        $this->auth    = Services::authentication();
        $this->_model  = model(UserModel::class);
        $this->authConfig = config('Auth');
    }

    public function activateAccount()
    {
        // throttler
        $this->_checkThrottler();

        $user = $this->_model->where('activate_hash', $this->request->getGet('token'))
            ->where('active', 0)
            ->first();

        if (null === $user) {
            return redirect()
                ->route('cus_login')
                ->with('error', lang('AuthCustomer.activationNoUser'));
        }

        // First things first - log the activation attempt.
        $this->_model->logActivationAttempt(
            $this->request->getGet('token'),
            $this->request->getIPAddress(),
            (string) $this->request->getUserAgent()
        );

        $user->activate();

        $this->_model->save($user);

        return redirect()
            ->route('cus_login')
            ->with('message', lang('AuthCustomer.registerSuccess'));
    }

    public function logout()
    {
        if (!$this->auth->check()) {
            return redirect()->route('cus_login');
        }
        $this->auth->logout();
        return redirect()->to(site_url('/'));
    }

    /**
     * Displays the Change Password form.
     */
    public function changePassword()
    {
        if (!$this->auth->check()) {
            return redirect()->route('cus_login');
        }

        if ( $this->user->user_type == UserTypeEnum::ADMIN ) {
            return redirect()->to(base_url());
        }

        //set breadcrumb
        BreadCrumbCell::add('Home', base_url());
        BreadCrumbCell::add(lang('CustomerProfile.my_account'), route_to('cus_profile'));

        $customer = model(CusModel::class)->queryCustomerByUserId($this->user->id)->first();

        if ( $this->request->getPost() ) {
            $this->_checkThrottler();
            return $this->attemptChangePass();
        }
        $this->_data['user'] = $customer;
        return $this->_render('customer/auth/change_pass', $this->_data);
    }

    /**
     * Verifies new password and saves the new password,
     * if they all pass validation.
     *
     * @return mixed
     */
    public function attemptChangePass()
    {
        $users = model(UserModel::class);
        $postData = $this->request->getPost();

        //get user Data
        if ( $this->auth->check() ) {
            $userData = $this->auth->user();
        } else return redirect()->route('login')->with('error', lang('Auth.loginRequired'));

        // check for old password
        if (! Password::verify((string) $postData['old_password'], $userData->password_hash)) {
            return redirect()->back()->withInput()->with('error', lang('CustomerProfile.invalid_old_password'));
        }
        $rules = [
            'password'	 => 'required|min_length[6]|alpha_numeric',
            'pass_confirm' => 'required|matches[password]',
        ];

        if (! $this->validate($rules))
        {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $user = $users->find($userData->id);

        if (is_null($user)) {
            return redirect()->back()->with('error', lang('Auth.forgotNoUser'));
        }
        //log old password
        $oldData = [
            'user_id' => $user->id,
            'old_password' => $user->password_hash
        ];

        // Success! Save the new password, and cleanup the reset hash.
        $user->password 		= $this->request->getPost('password');
        $user->reset_hash 		= null;
        $user->reset_at 		= date('Y-m-d H:i:s');
        $user->reset_expires    = null;
        $user->force_pass_reset = false;
        $users->save($user);

        // log the reset attempt.
        $users->logResetAttempt(
            $user->email,
            null,
            $this->request->getIPAddress(),
            (string)$this->request->getUserAgent(),
            $user->model_class,
            $oldData
        );

        return redirect()->back()->with('message', lang('CustomerProfile.changePassSuccess'));
    }

    public function forgotPassword()
    {
        //set breadcrumb
        BreadCrumbCell::add('Home', base_url());
        BreadCrumbCell::add(lang('AuthCustomer.reset_password'), route_to('cus_forgot_password'));

        $this->_data['user'] = $this->customer;
        return $this->_render('customer/auth/forgot-password', $this->_data);
    }
}
