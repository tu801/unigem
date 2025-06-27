<?php
namespace App\Traits;

trait SpamFilter
{
    /**
     * Check if the request is spam based on throttling.
     * Defaults to 1 request per second.
     *
     * @return \CodeIgniter\HTTP\Response|null
     */
    public function checkSpam()
    {
        $throttler = \Config\Services::throttler();

        // Limit to 1 request per second
        if ($throttler->check($this->request->getIPAddress(), 60, MINUTE) === false) {
            return service('response')->setStatusCode(429)->setJSON(lang('Site.tooManyRequests', [$throttler->getTokentime()]));
        }
    }
}