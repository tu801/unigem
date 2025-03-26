<?php
/**
 * @author tmtuan
 * @github https://github.com/tu801
 * created Date: 11/6/2023
 */

namespace App\Controllers;


use App\Libraries\BreadCrumb\BreadCrumbCell;
use Modules\Acp\Models\User\UserModel;
use Modules\Auth\Config\Services;

class Login extends BaseController
{
    protected $auth;
    protected $session;

    public function __construct()
    {
        parent::__construct();
        $this->session = Services::session();
        $this->auth = Services::authentication();
        $this->_model = model(UserModel::class);
    }

    public function login()
    {
        if ( logged_in() ) {
            return redirect()->route('cus_logout');
        }

        //set breadcrumb
        BreadCrumbCell::add('Home', base_url());
        BreadCrumbCell::add(lang('Home.cus_login'), route_to('cus_login'));

        return $this->_render('customer/auth/login', $this->_data);
    }

    public function actionLogin()
    {
        // throttler
        $this->_checkThrottler();

        $rules = [
            'username'	=> 'required',
            'password' => 'required',
        ];

        if (! $this->validate($rules, $this->messageValidate()))
        {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $remember = (bool)$this->request->getPost('remember');

        // Try to log them in...
        if (! $this->auth->attempt(['username' => $username, 'password' => $password], $remember))
        {
            return redirect()->back()->withInput()->with('error', $this->auth->error() ?? lang('AuthCustomer.badAttempt'));
        }

        // Is the user being forced to reset their password?
        if ($this->auth->user()->force_pass_reset === true)
        {
            $url = route_to('customer-reset-password') . '?token=' . $this->auth->user()->reset_hash;
            return redirect()
                ->to($url)
                ->withCookies();
        }

        $redirectURL = session('redirect_url') ?? base_url(route_to('cus_profile'));
        unset($_SESSION['redirect_url']);

        return redirect()
            ->to($redirectURL)
            ->withCookies()
            ->with('message', lang('AuthCustomer.loginSuccess'));
    }

    public function _checkThrottler()
    {
        $throttler = service('throttler');

        if ($throttler->check(md5($this->request->getIPAddress()), 4, MINUTE) === false) {
            return service('response')
                ->setStatusCode(429)
                ->setBody(lang('AuthCustomer.tooManyRequests', [$throttler->getTokentime()]));
        }
    }

    private function messageValidate()
    {
        return [
            'username'        => [
                'required'    => lang('AuthCustomer.username_required'),
            ],
            'password'     => [
                'required'        => lang('AuthCustomer.password_required'),
                'strong_password' => lang('AuthCustomer.password_strong_password'),
            ]
        ];
    }
}