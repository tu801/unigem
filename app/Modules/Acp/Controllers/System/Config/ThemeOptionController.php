<?php
/**
 * @author tmtuan
 * created Date: 10/20/2021
 * project: fox_cms
 */

namespace Modules\Acp\Controllers\System\Config;


use Modules\Acp\Controllers\AcpController;
use App\Enums\ThemeOptionEnum;
use App\Enums\UploadFolderEnum;
use Modules\Acp\Libraries\ThemeOptions;
use App\Models\AttachModel;
use App\Models\ConfigModel;

class ThemeOptionController extends AcpController
{
    private $themeOptions;

    public function __construct()
    {
        parent::__construct();

        $this->themeOptions = new ThemeOptions();

        if ( empty($this->_model)) {
            $this->_model = model(ConfigModel::class);
        }
    }

    public function index() {
        $this->_data['title']= lang("Config.theme_option_title");

        $optData = $this->themeOptions->getOptionGroups();
        $this->_data['cfData'] = $optData;
        $this->_render('\system\config\themeOption\index', $this->_data);
    }

    /**
     * save theme config options
     * @return mixed
     */
    public function saveOptions()
    {
        $postData = $this->request->getPost();
        if ( !isset($postData['option_group']) || empty($postData['option_group']) ) {
            return redirect()->back()->with('error', lang('Acp.invalid_request'));
        }

        if ( !in_array($postData['option_group'], ThemeOptionEnum::CONFIG_GROUP)) {
            return redirect()->back()->with('error', lang('Theme.invalid_option_group'));
        }

        switch ($postData['option_group']) {
            case 'logo':
                $this->themeOptions->saveLogoConfig($postData);
                break;
            case 'general':
                $this->themeOptions->saveGeneralConfig(
                    $postData,
                    ThemeOptionEnum::GENERAL_CONFIGS,
                    $this->currentLang->locale,
                    'general');
                break;
            case 'jewelry_block':
                $this->themeOptions->saveGeneralConfig(
                    $postData,
                    ThemeOptionEnum::JEWELRY_BLOCK,
                    $this->currentLang->locale);
                break;
            case 'gems_block':
                $this->themeOptions->saveGeneralConfig(
                    $postData,
                    ThemeOptionEnum::GEMS_BLOCK,
                    $this->currentLang->locale);
                break;
            case 'running_text_block':
                $this->themeOptions->saveGeneralConfig(
                    $postData,
                    ThemeOptionEnum::RUNNING_TEXT_BLOCK,
                    $this->currentLang->locale);
                break;
            
        }

        return redirect()->back()->with('message', lang('Config.update_theme_success'));

    }

    public function saveSlider()
    {
        $postData = $this->request->getPost();
        $data = $this->themeOptions->saveMainSlider($postData, $this->currentLang->locale);
        $response = [
            'error'   => 0,
            'message' => lang('Config.save_slider_success'),
            'data'    => $data,
        ];
        return $this->response->setJSON($response);
    }

    public function getSlider()
    {
        $data = $this->themeOptions->getMainSlider($this->currentLang->locale);
        $response = [
            'error' => 0,
            'data'  => $data,
        ];
        return $this->response->setJSON($response);
    }

    public function deleteSlider()
    {
        $postData = $this->request->getPost();

        $modelAttach     = model(AttachModel::class);
        $item = $modelAttach->find($postData['image']['id']);
        if ( isset($item->id) ) {
            delete_image($item->file_name, '/' . UploadFolderEnum::ATTACH . '/'.date_format(date_create($item->created_at), 'Y/m'));
            $modelAttach->delete($item->id);
        }

        $this->themeOptions->deleteMainSlider($postData, $this->currentLang->locale);
        $response = [
            'error' => 0,
            'message' => lang('Config.update_slider_success'),
        ];

        return $this->response->setJSON($response);
    }
}