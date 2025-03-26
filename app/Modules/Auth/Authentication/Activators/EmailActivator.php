<?php

namespace Modules\Auth\Authentication\Activators;

use Config\Email;
use Modules\Acp\Entities\User;
use Modules\Acp\Enums\UserTypeEnum;

/**
 * Class EmailActivator
 *
 * Sends an activation email to user.
 */
class EmailActivator extends BaseActivator implements ActivatorInterface
{
    /**
     * Sends an activation email
     *
     * @param User $user
     */
    public function send(?User $user = null): bool
    {
        $email  = service('email');
        $config = new Email();

        $settings = $this->getActivatorSettings();

        $mailMessage = $user->user_type == UserTypeEnum::CUSTOMER
                    ? 'customer/auth/emails/activation'
                    : $this->config->views['emailActivation'];

        $sent = $email->setFrom($settings->fromEmail ?? $config->fromEmail, $settings->fromName ?? $config->fromName)
            ->setTo($user->email)
            ->setSubject(lang('Auth.activationSubject'))
            ->setMessage(view($mailMessage, ['hash' => $user->activate_hash]))
            ->setMailType('html')
            ->send();

        if (! $sent) {
            $this->error = lang('Auth.errorSendingActivation', [$user->email]);

            return false;
        }

        return true;
    }
}
