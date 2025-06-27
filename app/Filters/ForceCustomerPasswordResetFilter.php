<?php

declare(strict_types=1);

/**
 * This file is part of CodeIgniter Shield.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace App\Filters;

use App\Enums\UserTypeEnum;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Shield\Authentication\Authenticators\Session;

/**
 * Force Password Reset Filter.
 */
class ForceCustomerPasswordResetFilter implements FilterInterface
{
    /**
     * Checks if a logged in user should reset their
     * password, and then redirect to the appropriate
     * page.
     *
     * @param array|null $arguments
     *
     * @return RedirectResponse|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! $request instanceof IncomingRequest) {
            return;
        }

        /** @var Session $authenticator */
        $authenticator = auth('session')->getAuthenticator();
        $user = $authenticator->getUser();

        if ($authenticator->loggedIn() && $authenticator->getUser()->requiresPasswordReset()) {
            if ( $user->user_type == UserTypeEnum::CUSTOMER ) {
                return redirect()->route('cus_change_password')->with('error', lang('Auth.passwordChangeRequired'));
            }
            return redirect()->to(config('Auth')->forcePasswordResetRedirect())
                ->with('error', lang('Auth.passwordChangeRequired'));
        }
    }

    /**
     * We don't have anything to do here.
     *
     * @param array|null $arguments
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null): void
    {
    }
}
