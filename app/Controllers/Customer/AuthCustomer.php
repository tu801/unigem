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

class AuthCustomer extends \App\Controllers\BaseController
{
    use Viewable;

    public function __construct()
    {
        parent::__construct();
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

        $identityModel = model(UserIdentityModel::class);
        $identity = $identityModel->getIdentityByType(
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

        // Success!
        return redirect()->to(route_to('cus_profile'))
            ->with('message', lang('Auth.registerSuccess'));
    }
}
