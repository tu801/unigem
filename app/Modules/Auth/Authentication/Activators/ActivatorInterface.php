<?php

namespace Modules\Auth\Authentication\Activators;

use Modules\Acp\Entities\User;

/**
 * Interface ActivatorInterface
 *
 * @package Modules\Auth\Authentication\Activators
 */
interface ActivatorInterface
{
    /**
     * Send activation message to user
     *
     * @param User $user
     *
     * @return bool
     */
    public function send(User $user = null): bool;

    /**
     * Returns the error string that should be displayed to the user.
     *
     * @return string
     */
    public function error(): string;

}
