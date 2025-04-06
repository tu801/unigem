<?php

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
     * @param object|string $curLang
     * @param bool $suffixes
     * @return string
     */
    function format_currency($number, $curLang=null, $suffixes=false) {
        // Handle when $curLang is a string (locale directly passed)
        $locale = is_string($curLang) ? $curLang : (is_object($curLang) && isset($curLang->locale) ? $curLang->locale : 'vi');
        
        if ($locale == "en") {
            return usd_encode($number, $suffixes);
        }
        
        // Default to Vietnamese currency format
        return vnd_encode($number, $suffixes);
    }
}



