<?php
/**
 * @author tmtuan
 * created Date: 8/13/2023
 */

namespace Modules\Acp\Libraries;

use CodeIgniter\Config\Services;
use App\Enums\CategoryEnum;
use App\Enums\ThemeOptionEnum;
use App\Models\AttachMetaModel;
use App\Models\AttachModel;
use App\Models\Blog\CategoryModel;
use App\Models\ConfigModel;

class ThemeOptions
{
    public $options;
    private $config_group_id = 'theme_options';
    private $_configModel;

    /**
     * List of input type that store data in json format
     */
    private $_dataTypeJsonInputList = [
        'image',
        'item_list'
    ];

    public function __construct()
    {
        $this->_configModel = model(ConfigModel::class);

        $configOptions = ThemeOptionEnum::CONFIG_GROUP;

        foreach ($configOptions as $configItem) {
            $this->options[$configItem] = [
                'title' => lang('Theme.config_group_'.$configItem)
            ];
        }
    }

    public function getOptionGroups() {
        $locale = Services::language()->getLocale();

        $this->getLogoItemConfig();
        // list general config
        $this->getThemeConfigRecord(
            'general',
            ThemeOptionEnum::GENERAL_CONFIGS,
            $locale,
            'general'
        );

        // list main slider config
        $this->getThemeConfigRecord(
            'main_slider',
            ThemeOptionEnum::MAIN_SLIDER,
            $locale
        );

        // list jewelry_block
        $this->getThemeConfigRecord(
            'jewelry_block',
            ThemeOptionEnum::JEWELRY_BLOCK,
            $locale
        );

        // list gems_block
        $this->getThemeConfigRecord(
            'gems_block',
            ThemeOptionEnum::GEMS_BLOCK,
            $locale
        );

        return $this->options;
    }

    /**
     * get config data for logo option group
     */
    public function getLogoItemConfig()
    {
        $logoConfig = [];

        foreach (ThemeOptionEnum::LOGO_CONFIG as $key => $input) {
            $configData = $this->_configModel->where('group_id', $this->config_group_id)
                        ->where('key', $key)
                        ->first();

            if (isset( $configData->id) ) {
                $logoConfig[$key] = [
                    'title' => lang('Theme.item_'.$key),
                    'input' => $input['input'],
                    'value' => $configData->value
                ];

            } else {
                $newConfig = [
                    'group_id'  => $this->config_group_id,
                    'title'     => lang('Theme.item_'.$key),
                    'key'       => $key,
                    'value'     => '',
                    'is_json' => ($input['input'] == 'image') ? 1 : 0
                ];
                $this->_configModel->insert($newConfig);

                $logoConfig[$key] = [
                    'title' => lang('Theme.item_'.$key),
                    'input' => $input['input'],
                    'value' => '',
                ];
            }
        }

        $this->options['logo']['items'] = $logoConfig;
    }

    /**
     * Get the config item in config table base on the group name and config_array
     * @param $group - The config group name
     * @param array $config_array - array of config item to load or create
     * @param string $locale - current locate
     * @param string $key_prefix - prefix key for the config
     */
    public function getThemeConfigRecord($group, array $config_array, string $locale, $key_prefix = '' )
    {
        if ( !isset($config_array) || empty($config_array) ) return;

        foreach ($config_array as $key => $item) {
            $configKey = !empty($key_prefix) ? "{$key_prefix}_{$key}_{$locale}" : "{$key}_{$locale}";
            $configData = $this->_configModel
                ->where('group_id', $this->config_group_id)
                ->where('key', $configKey)
                ->first();

            if (isset( $configData->id) ) {
                $itemConfig[$key] = [
                    'title' => lang('Theme.item_'.$key),
                    'input' => $item['input'],
                    'value' => $configData->value
                ];
            } else {
                $newConfig = [
                    'group_id'  => $this->config_group_id,
                    'title'     => lang('Theme.item_'.$key),
                    'key'       => $configKey,
                    'value'     => '',
                    'is_json' => (in_array($item['input'], $this->_dataTypeJsonInputList)) ? 1 : 0
                ];
                $this->_configModel->insert($newConfig);
                cache()->delete('_theme_options');

                $itemConfig[$key] = [
                    'title' => lang('Theme.item_'.$key),
                    'input' => $item['input'],
                    'value' => ''
                ];
            }
            // display description text
            if ( isset($item['desc']) && !empty($item['desc'])) {
                $itemConfig[$key]['desc'] = lang("Theme.item_{$item['desc']}");
            }

            /**
             * display default data for select input or item_list
             */
            if ( isset($item['data']) && !empty($item['data']) ) {
                $itemConfig[$key]['data'] = $this->{$item['data']}();
            }
        }

        $this->options[$group]['items'] = $itemConfig;
    }

    /**
     * Save config data from admin
     *
     * @param $input - Post data
     */
    public function saveLogoConfig($input)
    {
        foreach (ThemeOptionEnum::LOGO_CONFIG as $key => $item) {
            if ( !array_key_exists($key, $input) ) {
                continue;
            }

            $imageData = $this->getAttachImageData($input[$key]);
            $configData = $this->_configModel
                ->where('group_id', $this->config_group_id)
                ->where('key', $key)
                ->first();

            $configData->value = json_encode($imageData);

            $this->_configModel->update($configData->id, $configData);
        }
        cache()->delete('_theme_options');
    }

    /**
     * Save general config to database base on the config input type
     * @param $input
     * @param string $locale
     */
    public function saveGeneralConfig($postData, array $config_array, string $locale, $key_prefix = '')
    {
        foreach ($config_array as $key => $item) {
            if ( !array_key_exists($key, $postData) && $key != 'active' ) {
                continue;
            }

            $configKey = !empty($key_prefix) ? "{$key_prefix}_{$key}_{$locale}" : "{$key}_{$locale}";
            $configData = $this->_configModel
                ->where('group_id', $this->config_group_id)
                ->where('key', $configKey)
                ->first();

            switch ($item['input']) {
                case 'image':
                    $imageData = $this->getAttachImageData($postData[$key]);
                    $configData->value = json_encode($imageData);
                    break;
                case 'switch':
                    $configData->value = isset($postData[$key]) ? $postData[$key] : 0;
                    break;
                case 'item_list':
                    $configData->value = isset($postData[$key]) ? json_encode($postData[$key]) : json_encode([]);
                    break;
                default:
                    $configData->value = $postData[$key];
                    break;
            }

            $this->_configModel->update($configData->id, $configData);
        }
        cache()->delete('_theme_options');
    }

    private function getAttachImageData($id)
    {
        $_attachModel = model(AttachModel::class);
        $imageData = $_attachModel->find($id);
        $_attachModel->convertValue($imageData);
        return $imageData;
    }

    /**
     * get all config option in database for Frontend
     */
    public function getThemeOptions()
    {
        if ( !$themeOption = cache('_theme_options') ) {
            $themeOption = [];
            $configData = $this->_configModel
                ->where('group_id', $this->config_group_id)
                ->findAll();

            foreach ($configData as $item) {
                if ( $item->is_json == 1 ) {
                    $themeOption[$item->key] = json_decode($item->value);
                } else {
                    $themeOption[$item->key] = $item->value;
                }
            }

            cache()->save('_theme_options', $themeOption, 86400);
        }

        return $themeOption;
    }

    //================================main Slider===================================
    public function saveMainSlider($input, string $locale)
    {
        $metaAttach = model(AttachMetaModel::class);
        $value = [
            'image'         => $input['image']?? '',
            'title_small'   => $input['title_small'] ?? '',
            'title_big'     => $input['title_big'] ?? '',
            'slider_detail' => $input['slider_detail'] ?? '',
            'slider_url'    => $input['slider_url'] ?? '',
            'slider_button' => $input['slider_button'] ?? '',
        ];

        $configKey = "main_slider_{$locale}";
        $configData = $this->_configModel->where('group_id', $this->config_group_id)
            ->where('key', $configKey)
            ->first();

        $item = $metaAttach->insertOrUpdate([
            'id'       => $input['id'] ?? null,
            'mod_name' => $configKey,
            'mod_id'   => $configData->id,
            'mod_type' => 'single',
            'value'    => json_encode($value),
        ]);

        $id = $item->id;

        $sliderList = !empty($configData->value) ? json_decode($configData->value, true) : [];
        if (!in_array($id, $sliderList)) {
            $sliderList[] = $id;
        }
        $configData->value = json_encode(array_values($sliderList));
        $this->_configModel->update($configData->id, $configData);

        $value['id'] = $id;
        return $value;
    }

    public function getMainSlider(string $locale)
    {
        $configKey = "main_slider_{$locale}";
        $configData = $this->_configModel->where('group_id', $this->config_group_id)
            ->where('key', $configKey)
            ->first();
        $sliderList = !empty($configData->value) ? json_decode($configData->value, true) : [];

        $sliders = [];
        if (count($sliderList)) {
            $metaAttach = model(AttachMetaModel::class);
            $data       = $metaAttach->whereIn('id', $sliderList)->orderBy('id', 'DESC')->findAll();
            foreach ($data as $item) {
                $imagesData       = json_decode($item->images, true);
                $imagesData['id'] = $item->id;
                $sliders[]        = $imagesData;
            }
        }
        return $sliders;
    }

    public function deleteMainSlider($input, $locale)
    {
        $modelAttachMeta = model(AttachMetaModel::class);
        $modelAttach     = model(AttachModel::class);
        $configKey       = "main_slider_{$locale}";
        $configData      = $this->_configModel->where('group_id', $this->config_group_id)
            ->where('key', $configKey)
            ->first();

        $idAttachMeta = $input['id'];
        $idAttach     = $input['image']['id'];

        $modelAttachMeta->delete($idAttachMeta);
        $modelAttach->delete($idAttach);

        $sliderList = !empty($configData->value) ? json_decode($configData->value, true) : [];
        if (($key = array_search($idAttachMeta, $sliderList)) !== false) {
            unset($sliderList[$key]);
        }

        $configData->value = json_encode(array_values($sliderList));
        $this->_configModel->update($configData->id, $configData);
    }

    //================================custom functions===================================

    /**
    * @return array
    */
    public function __getProductCategories(){
        $session = session();
        $catData = model(CategoryModel::class)->getCategories(CategoryEnum::CAT_TYPE_PRODUCT, $session->lang->id);
        $responseData = [];
        if ( !empty($catData) ) {
            foreach ($catData as $item) {
                $responseData[$item->id] = $item->title;
            }
        }

        return $responseData;
    }
}