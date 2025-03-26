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
    public $imageThumb = ['height' => 150, 'width' => 150];
    public $scriptsPath = "scripts/";

    //--------------------------------------------------------------------
    // Layout for the views to extend
    //--------------------------------------------------------------------
    public $viewLayout = 'master';
    public $templatePath = "themes/";
    public $noimg = "images/no-image.svg";

}