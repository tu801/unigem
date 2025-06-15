<?php
namespace Modules\Ajax\Controllers;

use App\Models\Store\Customer\CustomerModel;
use CodeIgniter\Shield\Validation\ValidationRules;
use App\Enums\Store\CustomerActiveEnum;
use App\Models\User\UserModel;
use CodeIgniter\Shield\Models\UserIdentityModel;
use CodeIgniter\Shield\Traits\Viewable;
use CodeIgniter\I18n\Time;
use CodeIgniter\HTTP\IncomingRequest;
use App\Traits\Customer\CustomerValidationRules;

class CustomerController extends AjaxBaseController {
    use Viewable, CustomerValidationRules;

    public function __construct()
    {
        parent::__construct();

        $this->_model = model(CustomerModel::class);
    }

    public function logout() {
        $this->checkSpam();

        if (!auth()->loggedIn()) {
            return $this->respond([
                'status' => '400',
                'message' => lang('Auth.not_logged_in')
            ]);
        }

        auth()->logout();
        return $this->respond([
            'status' => '200',
            'message' => lang('Auth.logoutSuccess')
        ]);
    }

    public function login() {
        $this->checkSpam();

        if (auth()->loggedIn()) {
            return $this->respond([
                'code' => '400',
                'message' => lang('Auth.already_logged_in')
            ]);
        }

        // Validate here first, since some things,
        // like the password, can only be validated properly here.
        $rules = $this->getLoginValidationRules();

        if (! $this->validateData($this->request->getPost(), $rules, [], config('Auth')->DBGroup)) {
            return $this->respond([
                'code' => '401',
                'message' => $this->validator->getErrors()
            ]);
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
                return $this->respond([
                    'code' => '401',
                    'message' => $result->reason()
                ]);
            }

            $user = $authenticator->getUser(); 
            
            if ($user === null) {
                return $this->respond([
                    'code' => '401',
                    'message' => lang('Auth.activationBlocked')
                ]);
            }

            $customer = $this->_model->queryCustomerByUserId($user->id)->first();
            return $this->respond([
                'code' => '200',
                'message' => lang('Auth.loginSuccess'),
                'customer' => [
                    'code' => $customer->cus_code,
                    'email' => $customer->cus_email,
                    'full_name' => $customer->cus_full_name,
                    'phone' => $customer->cus_phone,
                    'address' => $customer->cus_address,
                    'country_id' => $customer->country_id,
                    'province_id' => $customer->province_id,
                    'district_id' => $customer->district_id,
                    'ward_id' => $customer->ward_id
                ]
            ]);
        } catch (\Exception $e) {
            $message = $authenticator->isPending() ? 
                lang('Auth.activationBlocked') :
                lang('Common.somethingWentWrong');

            return $this->respond([
                'code' => '500',
                'message' => $message
            ]);
        }
    }

    public function forgotPassword() {
        $this->checkSpam();

        if (auth()->loggedIn()) {
            return $this->respond([
                'code' => '400',
                'message' => lang('Auth.already_logged_in')
            ]);
        }

        // Check if the user exists
        $email = $this->request->getPost('email');
        
        if (empty($email)) {
            return $this->respond([
                'code' => '400',
                'message' => lang('Auth.emailRequired')
            ]);
        }

        $checkEmail = $this->_model->where('cus_email', $email)->first();
        if (empty($checkEmail)) {
            return $this->respond([
                'code' => '400',
                'message' => lang('Auth.emailNotFound')
            ]);
        }

        $user  = model(UserModel::class)->findByCredentials(['email' => $email]);

        if ($user === null) {
            return $this->respond([
                'code' => '404',
                'message' => lang('Auth.invalidEmail')
            ]);
        }
        
        // check if the user is active
        if ($user->active == CustomerActiveEnum::INACTIVE) {
            return $this->respond([
                'code' => '403',
                'message' => lang('Auth.needVerification')
            ]);
        }
        
        /** @var UserIdentityModel $identityModel */
        $identityModel = model(UserIdentityModel::class);

        // check if the user has requested recover password before
        $checkIdentity = $identityModel->where([
            'type'    => CustomerActiveEnum::FORGOT_PASSWORD_TYPE_EMAIL,
            'user_id' => $user->id
        ])->first();

        if ( isset( $checkIdentity->id) && !Time::now()->isAfter($checkIdentity->expires)) {
            return $this->respond([
                'code' => '200',
                'message' => lang('Auth.forgotPasswordSuccess')
            ]);
        }

        // Delete any previous magic-link identities
        $identityModel->deleteIdentitiesByType($user, CustomerActiveEnum::FORGOT_PASSWORD_TYPE_EMAIL);

        // Generate the code and save it as an identity
        helper('text');
        $token = random_string('crypto', 20);

        $identityModel->insert([
            'user_id' => $user->id,
            'type'    => CustomerActiveEnum::FORGOT_PASSWORD_TYPE_EMAIL,
            'secret'  => $token,
            'extra'   => lang('Auth.emailForgotPasswordExtra', [url_to('cus_recover_password') . '?token=' . $token]),
            'expires' => Time::now()->addSeconds(setting('Auth.magicLinkLifetime')),
        ]);

        /** @var IncomingRequest $request */
        $request = service('request');

        $ipAddress = $request->getIPAddress();
        $userAgent = (string) $request->getUserAgent();
        $date      = Time::now()->toDateTimeString();

        // Send the user an email with the code
        helper('email');
        $email = emailer(['mailType' => 'html'])
            ->setFrom(setting('Email.fromEmail'), setting('Email.fromName') ?? '');
        $email->setTo($user->email);
        $email->setSubject(lang('Auth.magicLinkSubject'));
        $email->setMessage($this->view(
            '\App\Views\Email\forgot_password_email.php',
            ['token' => $token, 'ipAddress' => $ipAddress, 'userAgent' => $userAgent, 'date' => $date],
            ['debug' => false]
        ));

        if ($email->send(false) === false) {
            log_message('error', $email->printDebugger(['headers']));

            return $this->respond([
                'code' => '422',
                'message' => lang('Auth.unableSendEmailToUser', [$user->email])
            ]);
        }

        // Clear the email
        $email->clear();

        return $this->respond([
            'code' => '200',
            'message' => lang('Auth.forgotPasswordSuccess')
        ]);
    }
}