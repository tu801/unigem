<?php

/**
 * Author: tmtuan
 * Created date: 05/04/2025
 **/

namespace App\Filters;


use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Acp\Enums\UserTypeEnum;
use CodeIgniter\HTTP\IncomingRequest;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! $request instanceof IncomingRequest) {
            return;
        }

        /** @var Session $authenticator */
        $authenticator = auth('session')->getAuthenticator();

        if ($authenticator->loggedIn()) {
            if (setting('Auth.recordActiveDate')) {
                $authenticator->recordActiveDate();
            }

            // Block inactive users when Email Activation is enabled
            $user = $authenticator->getUser();

            if ($user->user_type != UserTypeEnum::ADMIN) {
                return redirect('/');
            }

            return;
        }

        return redirect()->route('login');
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param null $arguments
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}