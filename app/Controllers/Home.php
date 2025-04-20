<?php

namespace App\Controllers;

use App\Enums\Post\PostPositionEnum;
use App\Enums\Post\PostStatusEnum;
use App\Enums\Store\Product\ProductStatusEnum;
use App\Libraries\SeoMeta\SeoMetaCell;
use App\Models\Blog\PostModel;
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
                    ->where('pdc.lang_id', $this->currentLang->id)
                    ->orderBy('product.id DESC')
                    ->where('pd_status', ProductStatusEnum::PUBLISH)
                    ->findAll(8);

        $postList = model(PostModel::class)
                    ->select('post.*, pc.title, pc.slug, pc.image')
                    ->join('post_content AS pc', 'pc.post_id = post.id')
                    ->where('pc.lang_id', $this->currentLang->id)
                    ->orderBy('post.id DESC')
                    ->where('post.post_status', PostStatusEnum::PUBLISH)
                    ->where('post.post_position', PostPositionEnum::TOP)
                    ->findAll(8);

        $this->_data['productList'] = $productList;
        $this->_data['postList'] = $postList;
        return $this->_render('home/index', $this->_data);
    }

    public function contactUs() {
        $this->page_title = lang('Home.contact_us');

        return $this->_render('home/contact', $this->_data);
    }

    public function error_404(){
        return $this->_render('errors/404', $this->_data);
    }
}
