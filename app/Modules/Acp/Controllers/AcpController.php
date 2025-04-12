<?php

namespace Modules\Acp\Controllers;

/**
 * Class BaseController
 */

use App\Traits\SetLang;
use App\Traits\SysConfig;
use App\Traits\SystemLog;
use CodeIgniter\Config\Services;
use CodeIgniter\Controller;
use Modules\Acp\Models\LangModel;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;

class AcpController extends Controller
{
    use SysConfig, SetLang, SystemLog;

    protected $_layout = "layout";
    protected $_data = [];
    protected $_model;
    protected $config;
    protected $user;
    protected $router;
    protected $currentAct;

    /**
     * @var int
     */
    protected $maxSizeImage;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = ['auth', 'form', 'acp', 'global', 'user', 'ecom'];

    /**
     * Instance of the main Request object.
     *
     * @var IncomingRequest|CLIRequest
     */
    protected $request;

    /**
     * Constructor.
     */
    public function __construct()
    {
        helper($this->helpers);
        $this->user = auth()->user();
    }

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        $this->router = Services::router();
        $this->_setLang();
        $this->checkMultilang();

        //load Config
        $this->config = config('Acp');
        $this->getConfig();

        $this->_data['layout'] = $this->_layout; //echo "<pre>".$this->router->controllerName();  exit;
        $this->_data['module'] = ADMIN_AREA;
        $controller = $this->router->controllerName();
        $controller = explode('\\', $controller);
        $this->_data['controller'] = strtolower(end($controller));
        $this->_data['method'] = $this->router->methodName();
        $this->currentAct = $this->_data['module'] . '/' . $this->_data['controller'] . '/' . $this->_data['method'];
    }

    /**
     * Setup view params and then render view
     * @param $viewPage
     * @param $data
     */
    public function _render($viewPage, $data)
    {
        $data['config'] = $this->config;
        $data['login_user'] = $this->user;
        $data['currentAct'] = $this->currentAct;
        $data['adminSlug'] = $this->_data['module'];
        $data['language'] = model(LangModel::class)->listLang();

        echo view($this->config->view . $viewPage, $data);
    }

    /**
     * process upload images file
     * @param $image
     * @param $validRules
     * @param array $info
     * @return array
     */
    public function upload_image($image, $validRules, $info = [])
    {
        $imgPath = '';

        $validated = $this->validate($validRules['validRules'], $validRules['errMess']);
        if (! $validated) {
            return ['error' => $this->validator->getErrors(), 'success' => false];
        } else {
            if (! $image->isValid()) return ['error' => $image->getErrorString() . '(' . $image->getError() . ')', 'success' => false];

            $file_name = $info['file_name'] ?? $image->getRandomName(); //exit($this->config->uploadFolder);
            $folderName = (isset($info['sub_folder']) && $info['sub_folder'] !== '') ? $info['sub_folder'] : '';

            if (!$image->move($this->config->uploadPath . $folderName, $file_name, true)) {
                return ['error' => $image->getError(), 'success' => false];
            } else {
                $imgPath = "{$folderName}/{$file_name}";
                return ['imgPath' => $imgPath, 'success' => true];
            }
        }
    }

    /**
     * Upload file
     * @param $file
     * @param $validRules
     * @param array $info
     * @return array
     */
    public function store_file($file, $validRules, $info = [])
    {
        $imgPath = '';

        $validated = $this->validate($validRules['validRules'], $validRules['errMess']);
        if (! $validated) {
            return ['error' => $this->validator->getErrors(), 'success' => false];
        } else {
            if (! $file->isValid()) return ['error' => $file->getErrorString() . '(' . $file->getError() . ')', 'success' => false];

            $file_name = $info['file_name'] ?? $file->getRandomName(); //exit($this->config->uploadFolder);
            $folderName = (isset($info['sub_folder']) && $info['sub_folder'] !== '') ? $info['sub_folder'] : '';

            if (!$file->move($this->config->uploadPath . $folderName, $file_name, true)) {
                return ['error' => $file->getError(), 'success' => false];
            } else {
                $imgPath = "{$folderName}/{$file_name}";
                return ['imgPath' => $imgPath, 'success' => true];
            }
        }
    }

    /**
     * generate default upload rule for image upload
     * @return array
     */
    public function _getDefaultUploadRule()
    {
        $mineType = $this->config->sys['default_mime_type'] ?? 'image,image/jpg,image/jpeg,image/gif,image/png';
        $maxUploadSize = (isset($this->config->sys['default_max_size']) && $this->config->sys['default_max_size'] > 0)
            ? $this->config->sys['default_max_size'] * 1024
            : 2048;

        $imgRules = [
            'image' => [
                'uploaded[image]',
                "mime_in[image,{$mineType}]",
                "max_size[image,{$maxUploadSize}]",
            ],
        ];
        $imgErrMess = [
            'image' => [
                'mime_in' => lang('Acp.invalid_image_file_type'),
                'max_size' => lang('Acp.image_to_large', [$this->config->sys['default_max_size']]),
            ]
        ];
        return ['validRules' => $imgRules, 'errMess' => $imgErrMess];
    }
}