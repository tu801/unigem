<?php

use CodeIgniter\I18n\Time;

if (!function_exists('vnd_decode')) {
    /**
     * convert VND currency value to number
     * @param $number
     * @return string|string[]|null
     */
    function vnd_decode($number) {
        return preg_replace('/,+/', '', $number);
    }
}

if (!function_exists('vnd_encode')) {
    /**
     * transform number to VND currency format
     * @param $number
     * @param bool $suffixes
     * @return string
     */
    function vnd_encode($number, $suffixes=false) {
        if (empty($number)) {
            return '0đ';
        }
        
        // Convert to integer for VND (remove decimal part)
        $number = floor((float)$number);
        
        if($suffixes){
            $Vnddot = number_format($number, 0, '', ',') . 'đ';
        } else {
            $Vnddot = number_format($number, 0, '', ',') . 'đ';
        }
        return $Vnddot;
    }
}

if (!function_exists('usd_encode')) {
    /**
     * transform number to USD currency format
     * @param $number
     * @param bool $suffixes
     * @return string
     */
    function usd_encode($number, $suffixes=false) {
        if (empty($number)) {
            return '0.00';
        }
        
        if($suffixes){
            $USDdot = '$' . number_format((float)$number, 2, '.', ',');
        } else {
            $USDdot = number_format((float)$number, 2, '.', ',');
        }
        return $USDdot;
    }
}

if (!function_exists('format_currency')) {
    /**
     * Format currency based on language locale
     * @param $number
     * @param object|string $currentLang
     * @param bool $suffixes
     * @return string
     */
    function format_currency($number) {
        $lang = session()->lang;
        $suffixes = $lang->currency_symbol;
        
        if ($lang->locale == "en") {
            return usd_encode($number, $suffixes);
        }
        
        // Default to Vietnamese currency format
        return vnd_encode($number, $suffixes);
    }
}

if (!function_exists('create_product_thumb')) {
    /**
     * create product thumbnail for attach file
     * @param object $attachFile
     * @return string product thumb file url
     */
    function create_product_thumb($attachFile) {
        $shopConfig     = config('Shop');
        $myTime         = Time::parse($attachFile->created_at);
        
        $productThumbName = $shopConfig->productThumbSize['height'].'-'.$shopConfig->productThumbSize['width'].'-'.$attachFile->file_name;
        $productThumbFile = 'uploads/attach/' . $myTime->format('Y/m').'/thumb/'.$productThumbName;

        // check if thumb image exist
        $productThumbFilePath = FCPATH . str_replace('/', DIRECTORY_SEPARATOR, $productThumbFile);
        if ( !file_exists($productThumbFilePath) ) {
            // create product thumbnail
            \Config\Services::image()
                ->withFile(FCPATH . str_replace('/', DIRECTORY_SEPARATOR, $attachFile->full_image))
                ->fit($shopConfig->productThumbSize['width'], $shopConfig->productThumbSize['height'], 'center')
                ->save($productThumbFilePath);
            return $productThumbFile;
        } 

        return $productThumbFile;
    }
}

