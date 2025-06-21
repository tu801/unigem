<?php

namespace App\Traits;

use CodeIgniter\Shield\Authentication\Actions\EmailActivator;
use CodeIgniter\Shield\Exceptions\LogicException;
use RuntimeException;
use CodeIgniter\I18n\Time;
use CodeIgniter\Shield\Traits\Viewable;

trait ActivationEmail
{
    use Viewable;

    public function getPendingLoginUser()
    {
        /** @var Session $authenticator */
        $authenticator = auth('session')->getAuthenticator();

        $user = $authenticator->getPendingUser();
        if ($user === null) {
            throw new RuntimeException('Cannot get the pending login User.');
        }

        return $user;
    }

    /**
     * Send activation email to the user.
     * 
     */
    public function sendActivationEmail($user)
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
}
