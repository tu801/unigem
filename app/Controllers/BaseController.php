<?php

namespace App\Controllers;

use App\Traits\SetLang;
use App\Traits\SysConfig;
use App\Traits\SystemLog;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Enums\UserTypeEnum;
use Modules\Acp\Libraries\ThemeOptions;
use Psr\Log\LoggerInterface;

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
    use SetLang, SystemLog, SysConfig;

    protected $_data = [];
    protected $_model;
    protected $config;
    protected $db;
    protected $user;
    protected $customer;
    protected $theme;
    
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
     * @var list<string>
     */
    protected $helpers = ['global', 'theme_config', 'ecom'];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    public function __construct()
    {
        $this->db = db_connect();
        helper($this->helpers);
        $this->config           = config('Site');
    }

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        $authenticator = auth('session')->getAuthenticator();

        // Preload any models, libraries, etc, here.
        $this->getConfig();
        $this->_setupTheme();
        $this->_setLang();

        if ($authenticator->loggedIn()) {
            $this->user = $authenticator->getUser();
            if ( $this->user->user_type == UserTypeEnum::CUSTOMER ) $this->customer = model(CusModel::class)->queryCustomerByUserId($this->user->id)->first();
        }
    }

    /**
     * setup theme options and set view path
     * @return void
     */
    private function _setupTheme($viewLayout = null)
    {
        $themeOption = new ThemeOptions();
        $this->_data['themeOption'] = $themeOption->getThemeOptions();

        $theme = $this->config->theme_name ?? $this->config->sys['default_theme_name'];
        $this->config->templatePath .= $theme;
        $this->config->noimg = "{$theme}/".$this->config->noimg;
        $this->config->view = 'App\Views';
        $this->theme = $theme;

        if ( !empty( $viewLayout ) ) {
            $this->config->viewLayout = $viewLayout;
        }

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
}
