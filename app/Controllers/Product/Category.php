<?php

/**
 * @author tmtuan
 * @github https://github.com/tu801
 * created Date: 11/8/2023
 */

namespace App\Controllers\Product;


use App\Controllers\BaseController;
use App\Enums\CategoryEnum;
use App\Enums\Store\Product\ProductStatusEnum;
use App\Libraries\BreadCrumb\BreadCrumbCell;
use App\Libraries\SeoMeta\SeoMetaCell;
use App\Libraries\SeoMeta\SeoMetaEnum;
use App\Models\Blog\CategoryModel;
use App\Models\Store\Product\ProductModel;

class Category extends BaseController
{
    private $_categoryModel;

    public function __construct()
    {
        parent::__construct();
        $this->_model                    = model(ProductModel::class);
        $this->_categoryModel            = model(CategoryModel::class);
    }

    public function list($slug)
    {
        $getData = $this->request->getGet();
        $category = $this->_categoryModel->join('category_content', 'category_content.cat_id = category.id', 'LEFT')
            ->where('category.cat_type', CategoryEnum::CAT_TYPE_PRODUCT)
            ->where('category_content.slug', $slug)
            ->where('category.cat_status', 'publish')->first();

        if (isset($category->id)) {
            if (isset($getData['category'])) {
                $this->_model->where('cat_id', $category->id);
                $this->_data['select_cat'] = $getData['category'];
            } else {
                $this->_data['select_cat'] = $category->id;
            }

            $catIn = [];
            if ( $category->parent_id == 0) {
                $catIn[] = $category->id;
                $childs = $this->_categoryModel->where('parent_id', $category->id)->findAll();
                if (count($childs) > 0) {
                    foreach ($childs as $child) {
                        $catIn[] = $child->id;
                    }
                }
            } else {
                $catIn[] = $category->id;
            }

            $this->_model->select('product.*')
                ->select('product.*, pdc.pd_name, pdc.pd_slug, pdc.pd_description, pdc.pd_weight, pdc.pd_size, pdc.pd_cut_angle, pdc.price, pdc.price_discount ')
                ->join('product_content AS pdc', 'pdc.product_id = product.id')
                ->where('pdc.lang_id', $this->currentLang->id)
                ->where('product.pd_status', ProductStatusEnum::PUBLISH)
                ->whereIn('product.cat_id', $catIn)
                ->orderBy('product.id DESC');

            $this->_data['data']                 = $this->_model->paginate();
            $this->_data['pager']                = $this->_model->pager;


            $this->_data['product_category']     = $this->_categoryModel->getCategories(CategoryEnum::CAT_TYPE_PRODUCT, $this->currentLang->id);

            //SEOData config
            SeoMetaCell::setCanonical(current_url());
            SeoMetaCell::setOgType(SeoMetaEnum::OG_TYPE_PROD);
            $meta_desc = $category->seo_meta->seo_description ? $category->seo_meta->seo_description : ($category->description ? $category->description : get_theme_config('general_seo_description'));
            SeoMetaCell::add('meta_desc', $meta_desc);
            SeoMetaCell::add('meta_keywords', $category->seo_meta->seo_keyword ?? get_theme_config('general_seo_keyword'));
            SeoMetaCell::add('og_title', $category->seo_meta->seo_title ?? $category->title);
            SeoMetaCell::add('og_site_name', get_theme_config('general_site_title'));
            SeoMetaCell::add('og_description', $meta_desc);
            SeoMetaCell::add('og_url', current_url());
            $og_img_data = get_theme_config('general_seo_open_graph_image');
            $ogp_img = isset($og_img_data->full_image) ? $og_img_data->full_image : $this->config->templatePath. '/unigem-logo.png';
            SeoMetaCell::add('og_image', base_url($ogp_img));

            //set breadcrumb
            BreadCrumbCell::add('Home', base_url());
            BreadCrumbCell::add($category->title, route_to('product_category', $category->slug));

            $this->page_title = $category->title;
            return $this->_render('product/shop-left-sidebar', $this->_data);
        } else {
            return $this->_render('errors/404', $this->_data);
        }
    }
}
