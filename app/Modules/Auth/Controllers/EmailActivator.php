<?php

/**
 * This file is part of CodeIgniter Shield.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Modules\Auth\Controllers;

use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\I18n\Time;
use CodeIgniter\Shield\Authentication\Authenticators\Session;
use CodeIgniter\Shield\Exceptions\LogicException;
use CodeIgniter\Shield\Exceptions\RuntimeException;
use CodeIgniter\Shield\Authentication\Actions\EmailActivator as BaseEmailActivator;

class EmailActivator extends BaseEmailActivator
{

    /**
     * Shows the initial screen to the user telling them
     * that an email was just sent to them with a link
     * to confirm their email address.
     */
    public function show(): string
    {
        /** @var Session $authenticator */
        $authenticator = auth('session')->getAuthenticator();

        $user = $authenticator->getPendingUser();
        if ($user === null) {
            throw new RuntimeException('Cannot get the pending login User.');
        }

        $userEmail = $user->email;
        if ($userEmail === null) {
            throw new LogicException(
                'Email Activation needs user email address. user_id: ' . $user->id
            );
        }

        $code = $this->createIdentity($user);

        /** @var IncomingRequest $request */
        $request = service('request');

        $ipAddress = $request->getIPAddress();
        $userAgent = (string) $request->getUserAgent();
        $date      = Time::now()->toDateTimeString();

        // Send the email
        helper('email');
        $email = emailer(['mailType' => 'html'])
            ->setFrom(setting('Email.fromEmail'), setting('Email.fromName') ?? '');
        $email->setTo($userEmail);
        $email->setSubject(lang('Auth.emailActivateSubject'));
        $email->setMessage($this->view(
            setting('Auth.views')['action_email_activate_email'],
            ['code'  => $code, 'ipAddress' => $ipAddress, 'userAgent' => $userAgent, 'date' => $date],
            ['debug' => false]
        ));

        if ($email->send(false) === false) {
            throw new RuntimeException('Cannot send email for user: ' . $user->email . "\n" . $email->printDebugger(['headers']));
        }

        // Clear the email
        $email->clear();

        // $sgMail = new \App\Libraries\SendGridMailer();
        // $sgMail->send($userEmail, lang('Auth.emailActivateSubject'), $this->view(
        //     setting('Auth.views')['action_email_activate_email'],
        //     ['code'  => $code, 'ipAddress' => $ipAddress, 'userAgent' => $userAgent, 'date' => $date],
        //     ['debug' => false]
        // ));

        // Display the info page
        return $this->view(setting('Auth.views')['action_email_activate_show'], ['user' => $user]);
    }

}
