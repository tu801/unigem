<?php

/**
 * @author tmtuan
 * created Date: 12-Mar-21
 */

use Modules\Acp\Models\LangModel;
use Modules\Acp\Models\LogModel;

if (!function_exists('insert_vue')) {
    /**
     * Returns the Vuejs file url
     */
    function insert_vue()
    {
        $environment = getenv('CI_ENVIRONMENT');
//        $vue = ($environment  == 'development') ? base_url('/scripts/vue/vue.global.js') : 'https://cdn.jsdelivr.net/npm/vue@3.1.5/dist/vue.global.prod.js';
        $vue = ($environment  == 'development') ? base_url('/scripts/vue-3-3-7/vue.global.min.js') : 'https://cdnjs.cloudflare.com/ajax/libs/vue/3.3.7/vue.global.prod.min.js';

        return $vue;
    }
}

if (!function_exists('return_bytes')) {
    /**
     * Function use with Megabyte value
     * @param $val
     * @return int|string value in Bytes
     */
    function return_bytes($size_str)
    {
        switch (substr($size_str, -1)) {
            case 'M':
            case 'm':
                return (int)$size_str * 1048576;
            case 'K':
            case 'k':
                return (int)$size_str * 1024;
            case 'G':
            case 'g':
                return (int)$size_str * 1073741824;
            default:
                return $size_str;
        }
    }
}


/**
 * @author brianha289
 */
if (!function_exists('obfuscate_email')) {
    /**
     * Partially hide email address
     * @access public
     * @param $email String email
     * @return string
     */
    function obfuscate_email($email)
    {
        $em   = explode("@", $email);
        $name = implode('@', array_slice($em, 0, count($em) - 1));
        $len  = floor(strlen($name) / 2);

        return substr($name, 0, $len) . str_repeat('*', $len) . "@" . end($em);
    }
}

if (!function_exists('support_langs')) {
    /**
     * @access public
     * @return array of list languages
     */
    function support_langs()
    {
        return [
            [
                'id' => 0,
                'lang_code' => 'en',
            ],
            [
                'id' => 1,
                'lang_code' => 'vi'
            ],
        ];
    }
}

if (!function_exists('vnd_decode')) {
    /**
     * convert VND currency value to number
     * @param $number
     * @return string|string[]|null
     */
    function vnd_decode($number) {
        return preg_replace('/,+/', '',$number);
    }
}

if (!function_exists('vnd_encode')) {
    /**
     * transform number to VND currency format
     * @param $number
     * @param bool $suffixes
     * @return string
     */
    function vnd_encode($number,$suffixes=false) {
        if($suffixes){
            $Vnddot = strrev(implode(',', str_split(strrev($number), 3))) . 'đ';
        } else {
            $Vnddot = strrev(implode(',', str_split(strrev($number), 3)));
        }
        return $Vnddot;
    }
}

if (!function_exists('getRandomString')) {
    function getRandomString($n): string
    {
        $characters   = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < $n; $i++) {
            $index        = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
        return $randomString;
    }
}


/**
 * log action
 */
if(!function_exists('logAction')){
    function logAction(array $data) {
        $user = auth()->user();
        $properties = ( isset($data['properties']) && !empty($data['properties'])) ? json_encode($data['properties']) : null;

        if ( !isset($data['lang_id']) || empty($data['lang_id']) || $data['lang_id'] == 0 ) {
            $privLang = model(LangModel::class)->getPrivLang();
            $data['lang_id'] = $privLang->id;
        }

        $logData = [
            'module' => 'acp',
            'title' => $data['title'] ?? null,
            'description' => $data['description'] ?? null,
            'properties' => $properties,
            'subject_id' => $data['subject_id'] ?? null,
            'subject_type' => $data['subject_type'] ?? null,
            'causer_id' => $user->id ?? null,
            'causer_type' => get_class($user),
            'lang_id' => $data['lang_id']
        ];
        $_logModel = model(LogModel::class);
        $_logModel->insert($logData);
    }
}
?>