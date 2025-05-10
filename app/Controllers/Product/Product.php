<?php
/**
 * @author tmtuan
 * @github https://github.com/tu801
 * created Date: 11/5/2023
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
use Libraries\Collection\Collection;

class Product extends BaseController
{
    private $_categoryModel;

    public function __construct()
    {
        parent::__construct();
        $this->_model                    = model(ProductModel::class);
        $this->_categoryModel            = model(CategoryModel::class);

    }

    public function list()
    {
        $getData = $this->request->getGet();

        if (isset($getData['category']) && count($getData['category']) > 0) {
            $catArr = [];
            foreach ($getData['category'] as $cat) {
                if ( is_int($cat) && $cat > 0 ) $catArr[] = esc($cat);
            }
            if ( count($catArr) )  $this->_model->whereIn('cat_id', $getData['category']);

            $this->_data['select_cat'] = $getData['category'];
        }

        if (isset($getData['query']) && !empty($getData['query'])) {
            $q = esc($getData['query']);
            $this->_model->like('pd_name', $q);
        }

        $this->_model
                ->select('product.*, pdc.pd_name, pdc.pd_slug, pdc.pd_weight, pdc.pd_size, pdc.pd_cut_angle, pdc.price, pdc.price_discount ')
                ->join('product_content AS pdc', 'pdc.product_id = product.id')
                ->where('pdc.lang_id', $this->currentLang->id)
                ->where('product.pd_status', ProductStatusEnum::PUBLISH)
                ->orderBy('product.id DESC');

        $this->_data['data']                 = $this->_model->paginate();
        $this->_data['pager']                = $this->_model->pager;

        $this->_data['product_category']     = $this->_categoryModel->getCategories(CategoryEnum::CAT_TYPE_PRODUCT, $this->currentLang->id);

        //SEOData config
        SeoMetaCell::setCanonical(current_url());
        SeoMetaCell::setOgType(SeoMetaEnum::OG_TYPE_PROD);
        $meta_desc = get_theme_config('general_seo_description');
        SeoMetaCell::add('meta_desc', $meta_desc);
        SeoMetaCell::add('meta_keywords', get_theme_config('general_seo_keyword'));
        SeoMetaCell::add('og_title', get_theme_config('general_site_title'));
        SeoMetaCell::add('og_site_name', get_theme_config('general_site_title'));
        SeoMetaCell::add('og_description', $meta_desc);
        SeoMetaCell::add('og_url', current_url());
        $og_img_data = get_theme_config('general_seo_open_graph_image');
        SeoMetaCell::add('og_image', base_url($og_img_data->full_image));

        //set breadcrumb
        BreadCrumbCell::add('Home', base_url());
        BreadCrumbCell::add(lang('Product.shop'), route_to('product_shop'));

        return $this->_render('product/shop-left-sidebar', $this->_data);
    }

    public function detail($slug)
    {
        $userIp = $this->request->getIPAddress();
        $viewedProducts = cache()->get('viewedProducts_' . $userIp);
        $product = $this->_model
            ->select('product.*, pdc.pd_name, pdc.pd_slug, pdc.pd_weight, pdc.pd_size, pdc.pd_cut_angle, pdc.price, pdc.price_discount, pd_tags, pd_description, product_info, seo_meta ')
            ->join('product_content AS pdc', 'pdc.product_id = product.id')
            ->where('pdc.lang_id', $this->currentLang->id)
            ->where('pdc.pd_slug', $slug)
            ->where('pd_status', ProductStatusEnum::PUBLISH)
            ->first();
        if (!isset($product->id)) {
            return $this->response->redirect(route_to('show_error'));
        }
        $this->_data['product'] = $product;

        // handle viewed product
        if ( empty($viewedProducts) ) {
            $viewedProducts = [$product];
            cache()->save('viewedProducts_'. $userIp, $viewedProducts, 86400); // save cache for 24 hours
        } else {
            $viewedCollection = new Collection($viewedProducts);
            $check = $viewedCollection->find(function ($item) use ($product) {
                return $item->id == $product->id;
            });
            if ( !isset($check->id) ) {
                $viewedProducts[] = $product;
                cache()->save('viewedProducts_'. $userIp, $viewedProducts, 86400); // save cache for 24 hours
            }
        }
        $this->_data['recentlyViewedProducts'] = $viewedProducts;

        // get related product
        $this->_data['relatedProducts'] = $this->_model
            ->select('product.*, pdc.pd_name, pdc.pd_slug, pdc.price, pdc.price_discount, pd_tags, pd_description')
            ->join('product_content AS pdc', 'pdc.product_id = product.id')
            ->where('pdc.lang_id', $this->currentLang->id)
            ->where('product.pd_status', ProductStatusEnum::PUBLISH)
            ->where('product.cat_id', $product->cat_id)
            ->where('product.id !=', $product->id)
            ->orderBy('product.id DESC')
            ->findAll(8);

        // SEOData config
        SeoMetaCell::setCanonical($product->url);
        SeoMetaCell::setOgType(SeoMetaEnum::OG_TYPE_PROD);
        SeoMetaCell::add('meta_desc', $product->product_meta['seo_description'] ?? get_theme_config('general_seo_description'));
        SeoMetaCell::add('meta_keywords', $product->product_meta['seo_keyword'] ?? get_theme_config('general_seo_keyword'));
        SeoMetaCell::add('og_title', $product->product_meta['seo_title'] ?? $product->pd_name);
        SeoMetaCell::add('og_site_name', get_theme_config('general_site_title'));
        SeoMetaCell::add('og_description', $product->product_meta['seo_description'] ?? get_theme_config('general_seo_description'));
        SeoMetaCell::add('og_url', $product->url);
        SeoMetaCell::add('og_image', $product->feature_image['full']);

        //set breadcrumb
        BreadCrumbCell::add('Home', base_url());
        BreadCrumbCell::add(lang('Product.shop'), route_to('product_shop'));
        BreadCrumbCell::add($product->pd_name, $product->url );

        return $this->_render('product/detail', $this->_data);
    }
}