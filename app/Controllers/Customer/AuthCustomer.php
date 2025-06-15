<?php

namespace App\Controllers\Customer;

use App\Libraries\BreadCrumb\BreadCrumbCell;
use CodeIgniter\I18n\Time;
use CodeIgniter\Shield\Authentication\Actions\EmailActivator;
use CodeIgniter\Shield\Authentication\Authenticators\Session;
use CodeIgniter\Shield\Exceptions\LogicException;
use CodeIgniter\Shield\Models\UserIdentityModel;
use CodeIgniter\Shield\Traits\Viewable;
use RuntimeException;
use App\Enums\Store\CustomerActiveEnum;

class AuthCustomer extends \App\Controllers\BaseController
{
    use Viewable;

    protected $identityModel;
    protected $customerModel;

    public function __construct()
    {
        parent::__construct();
        $this->identityModel = model(UserIdentityModel::class);
        $this->customerModel = model(\App\Models\Store\Customer\CustomerModel::class);
    }

    public function logout()
    {
        auth()->logout();

        return redirect()->to('/')->with('message', lang('Auth.successLogout'));;
    }

    public function activateAccount()
    {
        $this->page_title = lang('Customer.activate_account');
        /** @var Session $authenticator */
        $authenticator = auth('session')->getAuthenticator();

        $user = $authenticator->getPendingUser();
        if ($user === null) {
            throw new RuntimeException('Cannot get the pending login User.');
        }

        // send activation email
        $this->sendActivationEmail($user);

        return $this->_render('customer/auth/email_activation', $this->_data);
    }

    private function sendActivationEmail($user)
    {
        $emailActivator = new EmailActivator();

        $userEmail = $user->email;
        if ($userEmail === null) {
            throw new LogicException(
                'Email Activation needs user email address. user_id: ' . $user->id
            );
        }

        $code = $emailActivator->createIdentity($user);

        $ipAddress = $this->request->getIPAddress();
        $userAgent = (string) $this->request->getUserAgent();
        $date      = Time::now()->toDateTimeString();

        // Send the email
        helper('email');
        $email = emailer(['mailType' => 'html'])
            ->setFrom(setting('Email.fromEmail'), setting('Email.fromName') ?? '');
        $email->setTo($user->email);
        $email->setSubject(lang('Auth.emailActivateSubject'));
        $email->setMessage($this->view(
            '\App\Views\Email\email_activate_email',
            ['code'  => $code, 'ipAddress' => $ipAddress, 'userAgent' => $userAgent, 'date' => $date],
            ['debug' => false]
        ));

        if ($email->send(false) === false) {
            throw new RuntimeException('Cannot send email for user: ' . $user->email . "\n" . $email->printDebugger(['headers']));
        }

        // Clear the email
        $email->clear();
    }

    /**
     * Verifies the email address and code matches an
     * identity we have for that user.
     *
     * @return RedirectResponse|string
     */
    public function verify()
    {
        /** @var Session $authenticator */
        $authenticator = auth('session')->getAuthenticator();

        $postedToken = $this->request->getVar('token');

        $user = $authenticator->getPendingUser();
        if ($user === null) {
            throw new RuntimeException('Cannot get the pending login User.');
        }

        $identity = $this->identityModel->getIdentityByType(
            $user,
            Session::ID_TYPE_EMAIL_ACTIVATE
        );

        // No match - let them try again.
        if (! $authenticator->checkAction($identity, $postedToken)) {
            session()->setFlashdata('error', lang('Auth.invalidActivateToken'));

            return $this->_render('customer/auth/email_activation', $this->_data);
        }

        $user = $authenticator->getUser();

        // Set the user active now
        $user->activate();
        $customer = $this->customerModel->queryCustomerByUserId($user->id)->first();
        $this->customerModel->update($customer->id, ['active' => CustomerActiveEnum::ACTIVE]);

        // Success!
        return redirect()->to(route_to('cus_profile'))
            ->with('message', lang('Auth.registerSuccess'));
    }

    /**
     * Displays the recover password view.
     */
    public function recoverPasswordView() {
        $this->page_title = lang('Auth.recoverPassword');
        $token = $this->request->getGet('token') ?? '';
        $this->_data['token'] = $token;

        $identity = $this->identityModel->getIdentityBySecret(CustomerActiveEnum::FORGOT_PASSWORD_TYPE_EMAIL, $token);

        $identifier = $token ?? '';

        // No token found?
        if ($identity === null) {
            session()->setFlashdata('errors', lang('Auth.magicTokenNotFound'));

            return $this->_render('customer/auth/recover_password', $this->_data);
        }

        // Token expired?
        if (Time::now()->isAfter($identity->expires)) {
            session()->setFlashdata('errors', lang('Auth.magicLinkExpired'));

            return $this->_render('customer/auth/recover_password', $this->_data);
        }

        // get customer by user_id
        $customer = $this->customerModel->queryCustomerByUserId($identity->user_id)->first();

        if ( !isset($customer) ) {
            session()->setFlashdata('errors', lang('Auth.emailNotFound'));

            return $this->_render('customer/auth/recover_password', $this->_data);
        }

        $this->_data['customer'] = $customer;
        return $this->_render('customer/auth/recover_password', $this->_data);
    }

    public function recoverPassword()
    {
        $token = $this->request->getGet('token') ?? '';

        $identity = $this->identityModel->getIdentityBySecret(CustomerActiveEnum::FORGOT_PASSWORD_TYPE_EMAIL, $token);

        $identifier = $token ?? '';

        // No token found?
        if ($identity === null) {
            return redirect()->back()
                ->with('errors', lang('Auth.magicTokenNotFound'));
        }

        // Token expired?
        if (Time::now()->isAfter($identity->expires)) {
            return redirect()->back()->with('errors', lang('Auth.magicLinkExpired'));
        }

        $userModel = model(\App\Models\User\UserModel::class);
        // get customer account by user_id
        $customerAccount = $userModel->findById($identity->user_id);
        if ( !isset($customerAccount) ) {
            return redirect()->back()->with('errors', lang('Auth.emailNotFound'));
        }

        if ( $customerAccount->user_type != \App\Enums\UserTypeEnum::CUSTOMER ) {
            return redirect()->to('/')->with('errors', lang('Common.invalidRequest'));
        }

        if ( $customerAccount->active == CustomerActiveEnum::INACTIVE ) {
            return redirect()->to('/')->with('errors', lang('Auth.needVerification'));
        }

        // verify new password
        $validRules = [
            'password' => [
                'rules' => 'required|min_length[6]|max_length[255]|max_byte[72]',
                'errors' => [
                    'required' => lang('Auth.passwordRequired'),
                    'min_length' => lang('Auth.errorPasswordLength', [6]),
                    'max_length' => lang('Auth.passwordMaxLength'),
                    'max_byte' => lang('Auth.errorPasswordTooLongBytes', [72]),
                ]
            ],
            'password_confirm' => [
                'rules' => 'matches[password]',
                'errors' => [
                    'matches' => lang('Auth.passwordNotMatch'),
                ]
            ],
        ];

        //validate the input
        if (! $this->validate($validRules)) {
            //return the errors
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // update password
        try {
            $this->db->transBegin();

            // update customer account
            $customerAccount->password = $this->request->getPost('password');
            $userModel->save($customerAccount);

            // delete identity so it cannot be used again.
            $this->identityModel->delete($identity->id);

            // log Action
            $logData = [
                'title' => 'Customer Recover Password',
                'description' => lang('CustomerProfile.recoverPasswordLog', [$customerAccount->id, $customerAccount->username]),
                'properties' => $customerAccount,
                'subject_id' => $customerAccount->id,
                'subject_type' => \App\Models\User\UserModel::class,
            ];
            $this->logAction($logData);

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return redirect()->back()->withInput()->with('errors', lang('Common.internalServerError'));
            }

            $this->db->transCommit();
        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()->back()->withInput()->with('errors', lang('Common.internalServerError'));
        }

        // Redirect to login page
        return redirect()->route('cus_login')
            ->with('message', lang('Auth.recoverPasswordSuccess'));
    }

}
