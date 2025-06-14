<?php

/**
 * @author: tmtuan
 * @date: 2025-Apr-14
 */

namespace Modules\Ajax\Controllers;

use App\Traits\SetLang;
use CodeIgniter\Config\Services;
use CodeIgniter\RESTful\ResourceController;
use App\Traits\SpamFilter;

class AjaxBaseController extends ResourceController
{
    use SetLang, SpamFilter;

    /**
     *  helpers to be loaded automatically
     *
     * @var array
     */
    protected $helpers = ['global', 'api'];
    protected $_model;
    protected $currentLang;

    public function __construct()
    {
        helper($this->helpers);

        $this->_setLang();
    }

}
