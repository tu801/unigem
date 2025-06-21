<?php

namespace App\Controllers\Customer;

use App\Libraries\BreadCrumb\BreadCrumbCell;
use CodeIgniter\I18n\Time;
use CodeIgniter\Shield\Authentication\Authenticators\Session;
use CodeIgniter\Shield\Models\UserIdentityModel;
use RuntimeException;
use App\Enums\Store\CustomerActiveEnum;
use CodeIgniter\Shield\Authentication\Passwords\DictionaryValidator;

class AuthCustomer extends \App\Controllers\BaseController
{
    use \App\Traits\ActivationEmail;

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

        // send activation email
        $this->sendActivationEmail($this->getPendingLoginUser());

        return $this->_render('customer/auth/email_activation', $this->_data);
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

        // remove identity so it cannot be used again.
        $this->identityModel->delete($identity->id);

        // Success!
        return redirect()->to(route_to('cus_profile'))
            ->with('message', lang('Auth.registerSuccess'));
    }

    /**
     * Displays the recover password view.
     */
    public function recoverPasswordView()
    {
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

        if (!isset($customer)) {
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
        if (!isset($customerAccount)) {
            return redirect()->back()->with('errors', lang('Auth.emailNotFound'));
        }

        if ($customerAccount->user_type != \App\Enums\UserTypeEnum::CUSTOMER) {
            return redirect()->to('/')->with('errors', lang('Common.invalidRequest'));
        }

        if ($customerAccount->active == CustomerActiveEnum::INACTIVE) {
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
        $passwordChecker = new DictionaryValidator(config('Auth'));
        $result = $passwordChecker->check($this->request->getPost('password'), $customerAccount);
        if (! $result->isOk()) {
            return redirect()->back()->with('errors', $result->reason())->withInput();
        }

        // update password
        try {
            $this->db->transBegin();
            //record old password
            $oldData = [
                'id' => $customerAccount->id,
                'user_name' => $customerAccount->username,
                'old_password' => $customerAccount->password_hash
            ];

            // update customer account
            $customerAccount->password = $this->request->getPost('password');
            $userModel->save($customerAccount);

            // delete identity so it cannot be used again.
            $this->identityModel->delete($identity->id);

            // log Action
            $logData = [
                'title' => 'Customer Recover Password',
                'description' => lang('CustomerProfile.recoverPasswordLog', [$customerAccount->id, $customerAccount->username]),
                'properties' => $oldData,
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
