<?php
/**
 * @author tmtuan
 * @github https://github.com/tu801
 * created Date: 11/5/2023
 */

namespace App\Controllers\Product;


use App\Controllers\BaseController;
use App\Libraries\BreadCrumb\BreadCrumbCell;
use App\Libraries\SeoMeta\SeoMetaCell;
use App\Libraries\SeoMeta\SeoMetaEnum;
use Modules\Acp\Enums\CategoryEnum;
use Modules\Acp\Enums\Store\Product\ProductStatusEnum;
use Modules\Acp\Models\AttachMetaModel;
use Modules\Acp\Models\Blog\CategoryModel;
use Modules\Acp\Models\Store\Product\ProductManufacturer;
use Modules\Acp\Models\Store\Product\ProductMetaModel;
use Modules\Acp\Models\Store\Product\ProductModel;

class Product extends BaseController
{
    private $_categoryModel;
    private $_productManufacturerModel;
    private $_productMetaModel;
    private $_attachMetaModel;

    public function __construct()
    {
        parent::__construct();
        $this->_model                    = model(ProductModel::class);
        $this->_productManufacturerModel = model(ProductManufacturer::class);
        $this->_categoryModel            = model(CategoryModel::class);
        $this->_attachMetaModel          = model(AttachMetaModel::class);
        $this->_productMetaModel         = model(ProductMetaModel::class);

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

        if (isset($getData['manufacturer']) && count($getData['manufacturer']) > 0) {
            $this->_model->whereIn('manufacture_id', esc($getData['manufacturer']));
            $this->_data['select_manufacturer'] = $getData['manufacturer'];
        }

        if (isset($getData['query']) && !empty($getData['query'])) {
            $q = esc($getData['query']);
            $this->_model->like('pd_name', $q);
        }

        $this->_model->select('product.*')->orderBy('product.id DESC');

        $productCategory                     = $this->_categoryModel->getCategories(CategoryEnum::CAT_TYPE_PRODUCT, $this->_data['curLang']->id);
        $this->_data['product_category']     = $productCategory;
        $this->_data['product_manufacturer'] = $this->_productManufacturerModel->findAll();
        $this->_data['data']                 = $this->_model->paginate();
        $this->_data['pager']                = $this->_model->pager;

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

        return $this->_render('product/index', $this->_data);
    }

    public function detail($slug)
    {
        $product = $this->_model
            ->where('pd_slug', $slug)
            ->where('pd_status', ProductStatusEnum::PUBLISH)
            ->first();
        if (!isset($product->id)) {
            return $this->response->redirect(route_to('show_error'));
        }
        $this->_data['product'] = $product;

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