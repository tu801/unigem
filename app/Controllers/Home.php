<?php

namespace App\Controllers;

use App\Libraries\SeoMeta\SeoMetaCell;
use Modules\Acp\Models\Blog\CategoryContentModel;
use Modules\Acp\Models\Store\Product\ProductModel;

class Home extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        //SEOData config
        SeoMetaCell::setCanonical();
        SeoMetaCell::setOgType();
        SeoMetaCell::add('meta_desc', get_theme_config('general_seo_description'));
        SeoMetaCell::add('meta_keywords', get_theme_config('general_seo_keyword'));
        SeoMetaCell::add('og_title', get_theme_config('general_site_title'));
        SeoMetaCell::add('og_site_name', get_theme_config('general_site_title'));
        SeoMetaCell::add('og_description', get_theme_config('general_seo_description'));
        SeoMetaCell::add('og_url', base_url());
        $og_img_data = get_theme_config('general_seo_open_graph_image');
        if(isset($og_img_data->full_image)) {
            SeoMetaCell::add('og_image', base_url($og_img_data->full_image));
        }

        $this->_productModel = model(ProductModel::class);
        $this->_categoryContentsModel = model(CategoryContentModel::class);
    }

    public function index()
    {
        return $this->_render('home/index', $this->_data);
    }

    public function error_404(){
        $this->_render('errors\404', $this->_data);
    }


    public function maintenance(){
        $this->_render('errors\maintenance', $this->_data);
    }
}
