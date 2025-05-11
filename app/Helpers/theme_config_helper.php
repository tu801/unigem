<?php
/**
 * @author tmtuan
 * created Date: 10/20/2023
 */

use CodeIgniter\Config\Services;
use Modules\Acp\Libraries\ThemeOptions;

if (!function_exists('get_theme_config')) {
    /**
     * Returns the theme config value base on the current lang
     */
    function get_theme_config($key)
    {
        $locale = Services::language()->getLocale();
        $themeOption = new ThemeOptions();
        $optionData = $themeOption->getThemeOptions();

        switch ($key) {
            case 'site_logo':
                $res = $optionData['site_logo'] ?? null;
                break;
            case 'favicon':
                $res = $optionData['favicon'] ?? null;
                break;
            default:
                $optionKey = $key . '_' . $locale;
                $res = $optionData[$optionKey] ?? '';
                break;
        }

        return $res;
    }
}

if (!function_exists('get_slider_config')) {

    function get_slider_config()
    {
        $locale      = Services::language()->getLocale();
        $themeOption = new ThemeOptions();
        return $themeOption->getMainSlider($locale);
    }
}

if (!function_exists('get_logo_url')) {

    function get_logo_url($configs)
    {
        $logoConfig = get_theme_config('site_logo');

        if ( isset($logoConfig) ) {
            $url = base_url($logoConfig->full_image);
        } else {
            $url = base_url($configs->templatePath. '/unigem-logo.png');
        }

        return $url;
    }
}

if (!function_exists('get_favicon_url')) {

    function get_favicon_url($configs)
    {
        $logoConfig = get_theme_config('favicon');

        if ( isset($logoConfig) ) {
            $url = base_url($logoConfig->full_image);
        } else {
            $url = base_url($configs->templatePath. '/favicon.ico');
        }

        return $url;
    }
}

if (!function_exists('get_menu')) {

    function get_menu($location = null)
    {
        $menuModel = model(\App\Models\Blog\MenuModel::class);
        $menuItemModel = model(\App\Models\Blog\MenuItemsModel::class);
        $lang = session()->lang;

        if ( empty($location) ) {
            $menu = $menuModel
                ->where('status', 'publish')
                ->where('lang_id', $lang->id)
                ->where('location', null)
                ->first();
        } else {
            $menu = $menuModel
                ->like(['location' => "%{$location}%"])
                ->where('lang_id', $lang->id)
                ->where('status', 'publish')
                ->first();
        }

        if ( !isset($menu->id) ) {
            return false;
        }

        $menuItems = $menuItemModel
            ->where('menu_id', $menu->id)
            ->where('parent_id', 0)
            ->orderBy('order ASC')
            ->findAll();

        foreach ($menuItems as $item) {
            $childItems = $menuItemModel
                ->where('menu_id', $menu->id)
                ->where('parent_id', $item->id)
                ->orderBy('order ASC')
                ->findAll();

            if ( isset($childItems) && !empty($childItems) ) {
                $item->children = $childItems;
            } else {
                $item->children = [];
            }
        }

        $menu->menu_items = $menuItems;
        return $menu;
    }
}

if (!function_exists('get_social_links')) {
    /**
     * Returns all of the social links
     *
     * @return array
     */
    function get_social_links()
    {
        $socialConfigLinks = [
           'general_facebook_link',
           'general_youtube_link',
           'general_tiktok_link',
           'general_instagram_link',
           'general_twitter_x_link', 
           'general_pinterest_link', 
        ];

        $socialLinks = [];

        $locale      = Services::language()->getLocale();
        $themeOption = new ThemeOptions();
        $optionData = $themeOption->getThemeOptions();

        foreach ($socialConfigLinks as $key) {
            $optionKey = $key . '_' . $locale;
            $socialLinks[$key] = $optionData[$optionKey] ?? null;
        }

        return array_filter($socialLinks);
    }
}