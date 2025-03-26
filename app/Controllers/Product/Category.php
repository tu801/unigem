<?php
/**
 * @author tmtuan
 * @github https://github.com/tu801
 * created Date: 11/8/2023
 */

namespace App\Controllers\Product;


use App\Controllers\BaseController;
use App\Libraries\BreadCrumb\BreadCrumbCell;
use App\Libraries\SeoMeta\SeoMetaCell;
use App\Libraries\SeoMeta\SeoMetaEnum;
use Modules\Acp\Enums\CategoryEnum;
use Modules\Acp\Models\AttachMetaModel;
use Modules\Acp\Models\Blog\CategoryModel;
use Modules\Acp\Models\Store\Product\ProductManufacturer;
use Modules\Acp\Models\Store\Product\ProductMetaModel;
use Modules\Acp\Models\Store\Product\ProductModel;

class Category extends BaseController
{

    private $_productMetaModel;
    private $_categoryModel;
    private $_productManufacturerModel;

    public function __construct()
    {
        parent::__construct();
        $this->_model                    = model(ProductModel::class);
        $this->_productManufacturerModel = model(ProductManufacturer::class);
        $this->_categoryModel            = model(CategoryModel::class);
        $this->_productMetaModel         = model(ProductMetaModel::class);

    }

    public function list($slug)
    {
        $getData = $this->request->getGet();
        $category = $this->_categoryModel->join('category_content', 'category_content.cat_id = category.id', 'LEFT')
            ->where('category.cat_type', CategoryEnum::CAT_TYPE_PRODUCT)
            ->where('category_content.slug', $slug)
            ->where('category.cat_status', 'publish')->first();

        if(isset($category->id)) {
            if (isset($getData['category'])) {
                $this->_model->where('cat_id', $category->id);
                $this->_data['select_cat'] = $getData['category'];
            }else {
                $this->_data['select_cat'] = $category->id;
            }

            if (isset($getData['manufacturer'])) {
                $this->_model->where('manufacture_id', $getData['manufacturer']);
                $this->_data['select_manufacturer'] = $getData['manufacturer'];
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
            $meta_desc = $category->seo_meta->seo_description ? $category->seo_meta->seo_description : ( $category->description ? $category->description : get_theme_config('general_seo_description'));
            SeoMetaCell::add('meta_desc', $meta_desc);
            SeoMetaCell::add('meta_keywords', $category->seo_meta->seo_keyword ?? get_theme_config('general_seo_keyword'));
            SeoMetaCell::add('og_title', $category->seo_meta->seo_title ?? $category->title);
            SeoMetaCell::add('og_site_name', get_theme_config('general_site_title'));
            SeoMetaCell::add('og_description', $meta_desc);
            SeoMetaCell::add('og_url', current_url());
            $og_img_data = get_theme_config('general_seo_open_graph_image');
            SeoMetaCell::add('og_image', base_url($og_img_data->full_image));

            //set breadcrumb
            BreadCrumbCell::add('Home', base_url());
            BreadCrumbCell::add($category->title, route_to('product_category', $category->slug));

            return $this->_render('product/category', $this->_data);
        }else{
            return $this->_render('errors/404', $this->_data);
        }
    }
}