<?php
/**
 * @author: tmtuan
 * @date: 2025-Apr-14
 */
namespace Modules\Ajax\Controllers;

use App\Traits\SetLang;
use CodeIgniter\Config\Services;
use CodeIgniter\RESTful\ResourceController;

class AjaxBaseController extends ResourceController
{
    use SetLang;
    
    /**
     *  helpers to be loaded automatically
     *
     * @var array
     */
    protected $helpers = ['global', 'api'];
    protected $throttler;
    protected $_model;
    protected $currentLang;

    public function __construct()
    {
        helper($this->helpers);
        $this->throttler = \Config\Services::throttler();

        $this->_setLang();
    }

    public function _checkSpam() {
        //check spam
        if ($this->throttler->check($this->request->getIPAddress(), 20, MINUTE) === false)
        {
            return Services::response()->setStatusCode(429)->setJSON(lang('Site.tooManyRequests', [$this->throttler->getTokentime()]));
        }
    }
}