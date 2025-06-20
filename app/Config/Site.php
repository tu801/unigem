<?php
/**
 * Author: tmtuan
 * Created date: 20/10/2023
 * Project: Unigem
 **/

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Site extends BaseConfig {

    public $uploadFolder =  ROOTPATH."uploads".DIRECTORY_SEPARATOR;
    public $imageThumb = ['height' => 360, 'width' => 550];
    public $scriptsPath = "scripts/";
    public $sys;

    //--------------------------------------------------------------------
    // Layout for the views to extend
    //--------------------------------------------------------------------
    public $view;
    public $theme_name = "unigem";
    public $viewLayout = 'master';
    public $templatePath;
    public $no_img;

    /**
     * cache time to live for view cell
     */
    public $viewCellCacheTtl = 60*60; // 1 hour
    

    public function __construct() {
        $this->templatePath = "themes/{$this->theme_name}/";
        $this->no_img = $this->templatePath . 'images/no-image.svg';
    }

}