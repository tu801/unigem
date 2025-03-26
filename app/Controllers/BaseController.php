<?php

namespace App\Controllers;

use App\Models\CusModel;
use App\Traits\SetLang;
use CodeIgniter\Config\Services;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Acp\Libraries\ThemeOptions;
use Modules\Acp\Traits\SysConfig;
use Modules\Acp\Traits\SystemLog;
use Psr\Log\LoggerInterface;
use Config\Database;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    use SysConfig;
    use SetLang;
    use SystemLog;

    protected $_data = [];
    protected $_model;
    protected $config;
    protected $db;
    protected $user;
    protected $customer;

    /**
     * The name of the current theme.
     * Must be within /themes directory.
     */
    protected string $theme = 'default';

    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = ['text', 'global', 'theme_config', 'auth'];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * Constructor.
     */
    public function __construct()
    {
        helper($this->helpers);

        $this->db               = Database::connect(); //Load database connection
        $this->config           = config('Site');
        $this->getConfig();
        $this->_setupThem();
        $this->_setLang();

        if (logged_in()) {
            $this->user = user();
            $this->customer = model(CusModel::class)->queryCustomerByUserId($this->user->id)->first();
        }
    }

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        $router = Services::router();

        $controller = $router->controllerName(); $controller = explode('\\', $controller);
        $this->_data['controller'] = strtolower(end($controller));
        $this->_data['method'] = $router->methodName();
    }

    /**
     * Setup view variable and render
     * @param $viewPage
     * @param $data
     */
    public function _render($viewPage, $data){
        $data['configs'] = $this->config;

        $themePath = ROOTPATH . "/themes/{$this->theme}/";
        $renderer  = single_service('renderer', $themePath);

        return $renderer
            ->setData($data)
            ->render($viewPage);
    }

    private function _setupThem($viewLayout = null)
    {
        $themeOption = new ThemeOptions();
        $this->_data['themeOption'] = $themeOption->getThemeOptions();

        $theme = $this->config->sys['default_theme_name'] ?? 'default';
        $this->config->templatePath .= $theme;
        $this->config->noimg = "{$theme}/".$this->config->noimg;
        $this->config->view = 'App\Views';
        $this->theme = $theme;

        if ( !empty( $viewLayout ) ) {
            $this->config->viewLayout = $viewLayout;
        }

    }

    public function _checkThrottler()
    {
        $throttler = service('throttler');

        if ($throttler->check(md5($this->request->getIPAddress()), 4, MINUTE) === false) {
            return service('response')
                ->setStatusCode(429)
                ->setBody(lang('AuthCustomer.tooManyRequests', [$throttler->getTokentime()]));
        }
    }
}
