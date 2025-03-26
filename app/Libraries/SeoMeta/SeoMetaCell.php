<?php

namespace App\Libraries\SeoMeta;

use CodeIgniter\View\Cells\Cell;

class SeoMetaCell extends Cell
{
    protected string $view = 'seo_meta';
    public static $metaData = [];

    /**
     * canonical link
     * @var string
     */
    public static $canonical = '';

    /**
     * Open Graph type can be website | product | article
     * @var string
     */
    public static $og_type = '';

    public static function setOgType($type = '')
    {
        if ( $type == '' ) {
            self::$og_type = SeoMetaEnum::OG_TYPE_WEB;
        } else {
            self::$og_type = $type;
        }
    }

    public static function setCanonical($url = null)
    {
        if ( !empty($url) ) {
            self::$canonical = $url;
        } else {
            self::$canonical = base_url();
        }
    }

    public static function add($key, $val)
    {
        if (!$key OR !$val) return;
        self::$metaData[$key] = $val;
    }

    public function render(): string
    {
        return $this->view($this->view, [
            'og_type' => self::$og_type,
            'canonical' => self::$canonical,
            'seoData' => self::$metaData
        ]);
    }
}
