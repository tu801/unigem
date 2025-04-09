<?php
use Config\Services;
use CodeIgniter\Config\Config;

if (! function_exists('user_avata'))
{
    /**
     * Get user avata image link
     *
     * @return string
     */
//    function user_avata($user)
//    {
//        $config = config('Acp');
//        $avtImg = '';
//        if ( !empty($user->avata) ) {
//            $date = new \DateTime($user->created_at);
//            $avtImg = "uploads/".$user->avata;
//        } else {
//            $avtImg = "{$config->templatePath}dist/img/avatar.png";
//        }
//        return base_url($avtImg);
//    }
}

if (!function_exists('remove_vietnamese')) {
    /**
     * Remove Vietnamese String
     * @param $str
     * @return string|string[]
     */
    function remove_vietnamese($str) {
        $coDau = array("à", "á", "ạ", "ả", "ã", "â", "ầ", "ấ", "ậ", "ẩ", "ẫ", "ă",
            "ằ", "ắ", "ặ", "ẳ", "ẵ",
            "è", "é", "ẹ", "ẻ", "ẽ", "ê", "ề", "ế", "ệ", "ể", "ễ",
            "ì", "í", "ị", "ỉ", "ĩ",
            "ò", "ó", "ọ", "ỏ", "õ", "ô", "ồ", "ố", "ộ", "ổ", "ỗ", "ơ"
        , "ờ", "ớ", "ợ", "ở", "ỡ",
            "ù", "ú", "ụ", "ủ", "ũ", "ư", "ừ", "ứ", "ự", "ử", "ữ",
            "ỳ", "ý", "ỵ", "ỷ", "ỹ",
            "đ",
            "À", "Á", "Ạ", "Ả", "Ã", "Â", "Ầ", "Ấ", "Ậ", "Ẩ", "Ẫ", "Ă"
        , "Ằ", "Ắ", "Ặ", "Ẳ", "Ẵ",
            "È", "É", "Ẹ", "Ẻ", "Ẽ", "Ê", "Ề", "Ế", "Ệ", "Ể", "Ễ",
            "Ì", "Í", "Ị", "Ỉ", "Ĩ",
            "Ò", "Ó", "Ọ", "Ỏ", "Õ", "Ô", "Ồ", "Ố", "Ộ", "Ổ", "Ỗ", "Ơ"
        , "Ờ", "Ớ", "Ợ", "Ở", "Ỡ",
            "Ù", "Ú", "Ụ", "Ủ", "Ũ", "Ư", "Ừ", "Ứ", "Ự", "Ử", "Ữ",
            "Ỳ", "Ý", "Ỵ", "Ỷ", "Ỹ",
            "Đ", "ê", "ù", "à");

        $khongDau = array("a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a"
        , "a", "a", "a", "a", "a", "a",
            "e", "e", "e", "e", "e", "e", "e", "e", "e", "e", "e",
            "i", "i", "i", "i", "i",
            "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o"
        , "o", "o", "o", "o", "o",
            "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u",
            "y", "y", "y", "y", "y",
            "d",
            "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A"
        , "A", "A", "A", "A", "A",
            "E", "E", "E", "E", "E", "E", "E", "E", "E", "E", "E",
            "I", "I", "I", "I", "I",
            "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O"
        , "O", "O", "O", "O", "O",
            "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U",
            "Y", "Y", "Y", "Y", "Y",
            "D", "e", "u", "a");

        $str = str_replace($coDau, $khongDau, $str);

        return $str;
    }
}

if (!function_exists('clean_url')) {
    /**
     * Clearn the string and create SEO friendly Url
     * @param $text
     * @return false|string|string[]|null
     */
    function clean_url($text)
    {
        // Remove vietnamese
        $text = remove_vietnamese($text);

        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
}

if (!function_exists('array_key_first')) {
    /**
     * get the first key of an array
     * @param array $arr
     * @return int|string|null
     */
    function array_key_first(array $arr) {
        foreach($arr as $key => $unused) {
            return $key;
        }
        return NULL;
    }
}

if (!function_exists('create_thumb')) {
    /**
     * Create image thumb
     */
    function create_thumb($imageData, $configThumb = null){
        $configs = config('Acp');
        $imageLib = \Config\Services::image();

        //check path
        if ( !is_dir($imageData['path']) ) {
            mkdir($imageData['path'], 0755, true);
        }

        //check thumb size
        if ( !empty($configThumb) ) {
            $thumbWidth = $configThumb['width'];
            $thumbHeight = $configThumb['height'];
        } else {
            $thumbWidth = $configs->imageThumb['width'];
            $thumbHeight = $configs->imageThumb['height'];
        }

        $imageLib->withFile($imageData['original_image'])
            ->fit($thumbWidth, $thumbHeight, 'center')
            ->save($imageData['path']."/{$imageData['file_name']}");
    }
}

if (! function_exists('delete_image')) {
    /**
     * Delete image file
     * @param $name
     * @param string $path
     */
    function delete_image($name, $path = '')
    {
        if (empty($name) ) return;

        $config = config('Acp');
        $path = str_replace(array('..', '/', ':'), '\\', $path);
        $image_path = ($path !== '') ? $config->uploadPath . "{$path}\\" : $config->uploadPath . "\\";
        $image_thumb_path = $image_path . "thumb\\";

        //delete image file
        @unlink($image_path . $name);
        @unlink($image_thumb_path . $name);
    }
}

//Dynamically add CSS files to header page
if(!function_exists('add_css')){
    function add_css($file='')
    {
        $configs = config('Acp');
        $header_css = $configs->header_css;

        if(empty($file)){
            return;
        }

        if(is_array($file)){
            if(!is_array($file) && count($file) <= 0){
                return;
            }
            foreach($file AS $item){
                $header_css[] = $item;
            }
            $configs->header_css = $header_css;
        }else{
            $str = $file;
            $header_css[] = $str;
            $configs->header_css = $header_css;
        }
    }
}

//Dynamically add Javascript files to footer page
if(!function_exists('add_js')){
    function add_js($file='')
    {
        $configs = config('Acp');
        $footer_js  = $configs->footer_js;

        if(empty($file)){
            return;
        }

        if(is_array($file)){
            if(!is_array($file) && count($file) <= 0){
                return;
            }
            foreach($file AS $item){
                $footer_js[] = $item;
            }
            $configs->footer_js = $footer_js;
        }else{
            $str = $file;
            $header_js[] = $str;
            $configs->footer_js = $footer_js;
        }
    }
}

/**
 * print out css link to header template
 */
if(!function_exists('put_headers')){
    function put_headers()
    {
        $str = '';
        $configs = config('Acp');
        $header_css = $configs->header_css;
        if ( empty($header_css) ) return;
        foreach($header_css AS $item){
            if (filter_var($item, FILTER_VALIDATE_URL) === FALSE) {
                $str .= '<link rel="stylesheet" href="' . base_url("{$configs->templatePath}/") . $item . '" type="text/css" />' . "\n";
            } else {
                $str .= '<link rel="stylesheet" href="' . $item . '" type="text/css" />' . "\n";
            }
        }

        echo $str;
    }
}

/**
 * print out footer js to template
 */
if(!function_exists('put_footers')){
    function put_footers()
    {
        $str = '';
        $configs = config('Acp');
        $footer_js = $configs->footer_js;
        if ( empty($footer_js) ) return;
        foreach($footer_js AS $item){
            if (filter_var($item, FILTER_VALIDATE_URL) === FALSE) {
                $str .= '<script type="text/javascript" src="' . base_url("{$configs->templatePath}/") . $item . '"></script>' . "\n";
            } else {
                $str .= '<script type="text/javascript" src="' .  $item . '"></script>' . "\n";
            }
        }

        echo $str;
    }
}
