<?php
/**
 * Author: tmtuan
 * Created date: 11/16/2023
 * Project: Unigem
 **/

namespace Modules\Auth\Filters;


use Config\Services;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Acp\Enums\UserTypeEnum;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! function_exists('logged_in'))
        {
            helper('auth');
        }

        $authenticate = Services::authentication();

        // if no user is logged in then send to the login form
        if (! $authenticate->check())
        {
            session()->set('redirect_url', current_url());
            return redirect('login');
        }
        $user = user();

        if ( $user->user_type == UserTypeEnum::CUSTOMER ) {
            return redirect('/');
        }
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param null $arguments
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}