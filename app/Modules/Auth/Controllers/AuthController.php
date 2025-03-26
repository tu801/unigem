<?php

namespace Modules\Auth\Controllers;

use Modules\Acp\Models\User\UserModel;
use Modules\Acp\Entities\User;
use Modules\Auth\Config\Services;

class AuthController extends BaseController
{
    /**
     * @var Auth
     */
    protected $auth;

    /**
     * @var \CodeIgniter\Session\Session
     */
    protected $session;

    public function __construct()
    {
        // Most services in this controller require
        // the session to be started - so fire it up!
        $this->session = Services::session();

        $this->auth = Services::authentication();

    }

    //--------------------------------------------------------------------
    // Login/out
    //--------------------------------------------------------------------

    /**
     * Displays the login form, or redirects
     * the user to their destination/home if
     * they are already logged in.
     */
    public function login()
    {
        $this->_data['title']= lang("Auth.login_title");

        // No need to show a login form if the user
        // is already logged in.
        if ($this->auth->check())
        {
            $redirectURL = session('redirect_url') ?? site_url($this->config->landingRoute);
            unset($_SESSION['redirect_url']);

            return redirect()->to($redirectURL);
        }

        // Set a return URL if none is specified
        $_SESSION['redirect_url'] = session('redirect_url') ?? previous_url();

        $this->_render($this->config->views['login'], $this->_data);
    }

    /**
     * Attempts to verify the user's credentials
     * through a POST request.
     */
    public function attemptLogin()
    {
        $rules = [
            'login'	=> 'required',
            'password' => 'required',
        ];
        if ($this->config->validFields == ['email'])
        {
            $rules['login'] .= '|valid_email';
        }

        if (! $this->validate($rules))
        {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $login = $this->request->getPost('login');
        $password = $this->request->getPost('password');
        $remember = (bool)$this->request->getPost('remember');

        // Determine credential type
        $type = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Try to log them in...
        if (! $this->auth->attempt([$type => $login, 'password' => $password], $remember))
        {
            return redirect()->back()->withInput()->with('error', $this->auth->error() ?? lang('Auth.badAttempt'));
        }

        // Is the user being forced to reset their password?
        if ($this->auth->user()->force_pass_reset === true)
        {
            $url = route_to('reset-password') . '?token=' . $this->auth->user()->reset_hash;

            return redirect()
                ->to($url)
                ->withCookies();
        }

        $redirectURL = session('redirect_url') ?? site_url($this->config->landingRoute);
        unset($_SESSION['redirect_url']);

        return redirect()
            ->to($redirectURL)
            ->withCookies()
            ->with('message', lang('Auth.loginSuccess'));
    }

    /**
     * Log the user out.
     */
    public function logout()
    {
        if ($this->auth->check())
        {
            $this->auth->logout();
        }

        return redirect()->to(site_url('/'));
    }

    //--------------------------------------------------------------------
    // Register
    //--------------------------------------------------------------------

    /**
     * Displays the user registration page.
     */
    public function register()
    {
        $this->_data['title'] = lang('Auth.register_title');

        // check if already logged in.
        if ($this->auth->check()) {
            return redirect()->back();
        }

        // Check if registration is allowed
        if (! $this->config->allowRegistration)
        {
            return redirect()->back()->withInput()->with('error', lang('Auth.registerDisabled'));
        }

        $this->_render($this->config->views['register'], $this->_data);
    }

    /**
     * Attempt to register a new user.
     */
    public function attemptRegister()
    {
        // Check if registration is allowed
        if (! $this->config->allowRegistration)
        {
            return redirect()->back()->withInput()->with('error', lang('Auth.registerDisabled'));
        }

        $users = model(UserModel::class);

        // Validate here first, since some things,
        // like the password, can only be validated properly here.
        $rules = [
            'username'  	=> 'required|alpha_numeric_space|min_length[2]|is_unique[users.username]',
            'email'			=> 'required|valid_email|is_unique[users.email]',
        ];

        if (! $this->validate($rules))
        {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Validate passwords since they can only be validated properly here
        $rules = [
            'password'     => 'required|strong_password',
            'pass_confirm' => 'required|matches[password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Save the user
        $insertData = [
            'username' => $this->request->getPost('username'),
            'pwhash' => $this->auth->setPassword($this->request->getPost('password')),
            'email' => $this->request->getPost('email'),
            'gid' => 3
        ];
        $user              = new User($insertData);

        $this->config->requireActivation === null ? $user->activate() : $user->generateActivateHash();

        if (! $users->save($user))
        {
            return redirect()->back()->withInput()->with('errors', $users->errors());
        }

        if ($this->config->requireActivation !== null) {
            $activator = service('activator');
            $sent      = $activator->send($user);

            if (! $sent) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', $activator->error() ?? lang('Auth.unknownError'));
            }

            // Success!
            return redirect()
                ->route('login')
                ->with('message', lang('Auth.activationSuccess'));
        }

        // Success!
        return redirect()
            ->route('login')
            ->with('message', lang('Auth.registerSuccess'));
    }

    //--------------------------------------------------------------------
    // Force Change Password
    //--------------------------------------------------------------------

    /**
     * Displays the Change Password form.
     */
    public function changePassword()
    {
        $this->_data['title']= lang("Auth.changePassword_title");

        if ( $this->auth->check() ) {
            $user = $this->auth->user();
        } else return redirect()->route('login')->with('error', lang('Auth.loginRequired'));

        $this->_data['userData'] = $user;
        $this->_render('Modules\Auth\Views\auth\change_pass', $this->_data);

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

        //get user Data
        if ( $this->auth->check() ) {
            $userData = $this->auth->user();
        } else return redirect()->route('login')->with('error', lang('Auth.loginRequired'));

        $rules = [
            'password'	 => "required|min_length[{$this->config->minimumPasswordLength }]|strong_password",
            'pass_confirm' => 'required|matches[password]',
        ];

        if ( $userData->username !== $this->request->getPost('username')){
            return redirect()->back()->withInput()->with('error', lang('Auth.invalid_request'));
        }

        if (! $this->validate($rules))
        {
            return redirect()->back()->withInput()->with('errors', $users->errors());
        }

        $user = $users->where('username', $this->request->getPost('username'))
            ->where('id', $userData->id)
            ->first();

        if (is_null($user))
        {
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
            $this->request->getPost('username'),
            $this->request->getPost('token'),
            $this->request->getIPAddress(),
            (string)$this->request->getUserAgent(),
            $user->model_class,
            $oldData
        );

        $redirectURL = session('redirect_url') ?? '/';
        unset($_SESSION['redirect_url']);

        return redirect()->to($redirectURL)->with('message', lang('Auth.loginSuccess'));
    }

    //--------------------------------------------------------------------
    // Forgot Password
    //--------------------------------------------------------------------

    /**
     * Displays the forgot password form.
     */
    public function forgotPassword()
    {
        $this->_data['title'] = lang('Auth.forgotPassword');
        // check if already logged in.
        if ($this->auth->check()) {
            return redirect()->back();
        }

        if ($this->config->activeResetter === null) {
            return redirect()
                ->route('login')
                ->with('error', lang('Auth.forgotDisabled'));
        }

        $this->_render($this->config->views['forgot'], $this->_data);
    }

    /**
     * Attempts to find a user account with that password
     * and send password reset instructions to them.
     */
    public function attemptForgot()
    {
        if ($this->config->activeResetter === null) {
            return redirect()
                ->route('login')
                ->with('error', lang('Auth.forgotDisabled'));
        }

        $rules = [
            'email' => [
                'label' => lang('Auth.emailAddress'),
                'rules' => 'required|valid_email',
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $users = model(UserModel::class);

        $user = $users->where('email', $this->request->getPost('email'))->first();
        if (is_null($user))
        {
            return redirect()->back()->with('error', lang('Auth.forgotNoUser'));
        }

        // Save the reset hash 
        $user->generateResetHash();
        $users->save($user);

        $resetter = service('resetter');
        $sent     = $resetter->send($user);

        if (! $sent) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $resetter->error() ?? lang('Auth.unknownError'));
        }

        return redirect()
            ->route('reset-password')
            ->with('message', lang('Auth.forgotEmailSent'));
    }

    /**
     * Displays the Reset Password form.
     */
    public function resetPassword()
    {
        if ($this->config->activeResetter === null) {
            return redirect()
                ->route('login')
                ->with('error', lang('Auth.forgotDisabled'));
        }

        $this->_data['title'] = lang('Auth.resetPassword');
        $this->_data['token'] = $this->request->getGet('token');

        $this->_render($this->config->views['reset'], $this->_data);
    }

    /**
     * Verifies the code with the email and saves the new password,
     * if they all pass validation.
     *
     * @return mixed
     */
    public function attemptReset()
    {
        if ($this->config->activeResetter === null) {
            return redirect()
                ->route('login')
                ->with('error', lang('Auth.forgotDisabled'));
        }

        $users = model(UserModel::class);

        // First things first - log the reset attempt.
        $users->logResetAttempt(
            $this->request->getPost('email'),
            $this->request->getPost('token'),
            $this->request->getIPAddress(),
            (string) $this->request->getUserAgent()
        );

        $rules = [
            'token'		=> 'required',
            'email'		=> 'required|valid_email',
            'password'	 => 'required|strong_password',
            'pass_confirm' => 'required|matches[password]',
        ];

        if (! $this->validate($rules))
        {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $user = $users->where('email', $this->request->getPost('email'))
            ->where('reset_hash', $this->request->getPost('token'))
            ->first();

        if (is_null($user))
        {
            return redirect()->back()->with('error', lang('Auth.forgotNoUser'));
        }

        // Reset token still valid?
        if (! empty($user->reset_expires) && time() > $user->reset_expires->getTimestamp()) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', lang('Auth.resetTokenExpired'));
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
            $this->request->getPost('email'),
            $this->request->getPost('token'),
            $this->request->getIPAddress(),
            (string)$this->request->getUserAgent(),
            $user->model_class,
            $oldData
        );

        return redirect()->route('login')->with('message', lang('Auth.resetSuccess'));
    }

    //--------------------------------------------------------------------
    // Activate Account
    //--------------------------------------------------------------------
    /**
     * Activate account.
     *
     * @return mixed
     */
    public function activateAccount()
    {
        $users = model(UserModel::class);

        // First things first - log the activation attempt.
        $users->logActivationAttempt(
            $this->request->getGet('token'),
            $this->request->getIPAddress(),
            (string) $this->request->getUserAgent()
        );

        $throttler = service('throttler');

        if ($throttler->check(md5($this->request->getIPAddress()), 2, MINUTE) === false) {
            return service('response')
                ->setStatusCode(429)
                ->setBody(lang('Auth.tooManyRequests', [$throttler->getTokentime()]));
        }

        $user = $users->where('activate_hash', $this->request->getGet('token'))
            ->where('active', 0)
            ->first();

        if (null === $user) {
            return redirect()
                ->route('login')
                ->with('error', lang('Auth.activationNoUser'));
        }

        $user->activate();

        $users->save($user);

        return redirect()
            ->route('login')
            ->with('message', lang('Auth.registerSuccess'));
    }

    /**
     * Resend activation account.
     *
     * @return mixed
     */
    public function resendActivateAccount()
    {
        if ($this->config->requireActivation === null) {
            return redirect()
                ->route('login');
        }

        $throttler = service('throttler');

        if ($throttler->check(md5($this->request->getIPAddress()), 2, MINUTE) === false) {
            return service('response')
                ->setStatusCode(429)
                ->setBody(lang('Auth.tooManyRequests', [$throttler->getTokentime()]));
        }

        $login = urldecode($this->request->getGet('login'));
        $type  = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $users = model(UserModel::class);

        $user = $users->where($type, $login)
            ->where('active', 0)
            ->first();

        if (null === $user) {
            return redirect()
                ->route('login')
                ->with('error', lang('Auth.activationNoUser'));
        }

        $activator = service('activator');
        $sent      = $activator->send($user);

        if (! $sent) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $activator->error() ?? lang('Auth.unknownError'));
        }

        // Success!
        return redirect()
            ->route('login')
            ->with('message', lang('Auth.activationSuccess'));
    }

}