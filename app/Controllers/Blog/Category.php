<?php
/**
 * @author tmtuan
 * created Date: 10/20/2023
 */

namespace App\Controllers\Blog;


use App\Controllers\BaseController;
use App\Enums\CategoryEnum;
use App\Libraries\BreadCrumb\BreadCrumbCell;
use App\Libraries\SeoMeta\SeoMetaCell;
use App\Libraries\SeoMeta\SeoMetaEnum;
use Modules\Acp\Models\Blog\CategoryModel;
use Modules\Acp\Models\Blog\PostModel;

class Category extends  BaseController
{
    protected $_modelPost;

    public function __construct()
    {
        parent::__construct();
        $this->_model = model(CategoryModel::class);
        $this->_modelPost = model(PostModel::class);
    }

    public function list($slug)
    {
        $item = $this->_model->join('category_content', 'category_content.cat_id = category.id')
            ->where('category_content.slug', $slug)
            ->where('lang_id', $this->currentLang->id)
            ->first();
        if (isset($item->id)) {
            $postCategory = $this->_modelPost->join('post_content', 'post_content.post_id = post.id')
                ->join('post_categories', "post_categories.cat_id = {$item->id} AND post_categories.post_id = post.id")
                ->where('lang_id',  $this->currentLang->id)
                ->where('post_status', 'publish')
                ->select('post.*,post_content.*');

            $this->page_title = $item->title;
            $this->_data['category'] = $item;
            $this->_data['post_category'] = $postCategory->paginate(6);
            $this->_data['pager'] = $this->_modelPost->pager;

            //SEOData config
            SeoMetaCell::setCanonical(current_url());
            SeoMetaCell::setOgType(SeoMetaEnum::OG_TYPE_ARTCL);
            $meta_desc = $item->seo_meta->seo_description ? $item->seo_meta->seo_description : ( $item->description ? $item->description : get_theme_config('general_seo_description'));
            SeoMetaCell::add('meta_desc', $meta_desc);
            SeoMetaCell::add('meta_keywords', $item->seo_meta->seo_keyword ?? get_theme_config('general_seo_keyword'));
            SeoMetaCell::add('og_title', $item->seo_meta->seo_title ?? $item->title);
            SeoMetaCell::add('og_site_name', get_theme_config('general_site_title'));
            SeoMetaCell::add('og_description', $meta_desc);
            SeoMetaCell::add('og_url', current_url());
            $og_img_data = get_theme_config('general_seo_open_graph_image');
            $ogp_img = isset($og_img_data->full_image) ? $og_img_data->full_image : $this->config->templatePath. '/unigem-logo.png';
            SeoMetaCell::add('og_image', base_url($ogp_img));

            //set breadcrumb
            BreadCrumbCell::add('Home', base_url());
            BreadCrumbCell::add($item->title, base_url(route_to('category_page', $item->slug)));

            if ( $item->cat_type == CategoryEnum::CAT_TYPE_PRODUCT ) return redirect(route_to('product_category', $item->slug));
            
            return $this->_render('blog/category/blog-grid', $this->_data);
        } else {
            return $this->_render('errors/404', $this->_data);
        }
    }
}