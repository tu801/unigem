<?php

namespace App\Controllers;

use App\Enums\Store\Product\ProductStatusEnum;
use App\Libraries\SeoMeta\SeoMetaCell;
use App\Models\Store\Product\ProductModel;

class Home extends BaseController
{
    protected $_productModel;

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

    }

    public function index(): string
    {
        $productList = model(ProductModel::class)
                    ->select('product.*, pdc.pd_name, pdc.pd_slug, pdc.price, pdc.price_discount ')
                    ->join('product_content AS pdc', 'pdc.product_id = product.id')
                    ->where('pdc.lang_id', $this->_data['curLang']->id)
                    ->orderBy('product.id DESC')
                    ->where('pd_status', ProductStatusEnum::PUBLISH)
                    ->findAll(8);

        $this->_data['productList'] = $productList;
        return $this->_render('home/index', $this->_data);
    }
}
