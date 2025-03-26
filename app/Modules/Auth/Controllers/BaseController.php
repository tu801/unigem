<?php
namespace Modules\Auth\Controllers;

/**
 * Class BaseController
 */

use CodeIgniter\Controller;

class BaseController extends Controller
{
    protected $_layout = 'default';
    protected $_data = [];
    protected $config;
    protected $user;
	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = ['auth'];

	/**
	 * Constructor.
	 */
	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);

		//load Config
        $this->config = config('Auth');

        
        $this->_data['layout'] = $this->_layout;
	}

	public function _render($viewPage, $data){
	    $data['config'] = $this->config;
        echo view($viewPage, $data);
    }
}
