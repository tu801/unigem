<?php

namespace Modules\Auth\Authentication\Resetters;

use Config\Email;
use Modules\Acp\Entities\User;
use Modules\Acp\Enums\UserTypeEnum;

/**
 * Class EmailResetter
 *
 * Sends a reset password email to user.
 */
class EmailResetter extends BaseResetter implements ResetterInterface
{
    /**
     * Sends a reset email
     *
     * @param User $user
     */
    public function send(?User $user = null): bool
    {
        $email  = service('email');
        $config = new Email();

        $settings = $this->getResetterSettings();

        $mailMessage = $user->user_type == UserTypeEnum::CUSTOMER
                    ? 'customer/auth/emails/forgot'
                    : $this->config->views['emailForgot'];

        $sent = $email->setFrom($settings->fromEmail ?? $config->fromEmail, $settings->fromName ?? $config->fromName)
            ->setTo($user->email)
            ->setSubject(lang('Auth.forgotSubject'))
            ->setMessage(view($mailMessage, ['hash' => $user->reset_hash]))
            ->setMailType('html')
            ->send();

        if (! $sent) {
            $this->error = lang('Auth.errorEmailSent', [$user->email]);

            return false;
        }

        return true;
    }
}
