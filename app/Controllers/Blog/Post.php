<?php
/**
 * @author tmtuan
 * created Date: 10/20/2023
 * project: tuanthoamedia
 */

namespace App\Controllers\Blog;


use App\Controllers\BaseController;
use App\Enums\Post\PostTypeEnum;
use App\Libraries\BreadCrumb\BreadCrumbCell;
use App\Libraries\SeoMeta\SeoMetaCell;
use App\Libraries\SeoMeta\SeoMetaEnum;
use App\Models\Blog\TagsModel;
use Modules\Acp\Models\Blog\PostModel;

class Post extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->_model = model(PostModel::class);
    }

    public function detail($slug)
    {
        $this->_model->join('post_content', 'post_content.post_id = post.id')
            ->where('post_content.lang_id', $this->currentLang->id)
            ->where('post_status', 'publish')
            ->where('slug', $slug);

        $item = $this->_model->first();

        if (isset($item->id)) {
            // SEOData config
            SeoMetaCell::setCanonical($item->url);
            SeoMetaCell::setOgType(SeoMetaEnum::OG_TYPE_ARTCL);
            SeoMetaCell::add('meta_desc', $item->seo_meta->seo_description ?? $item->description ?? get_theme_config('general_seo_description'));
            SeoMetaCell::add('meta_keywords', $item->seo_meta->seo_keyword ?? get_theme_config('general_seo_keyword'));
            SeoMetaCell::add('og_title', $item->seo_meta->seo_title ?? $item->title);
            SeoMetaCell::add('og_site_name', get_theme_config('general_site_title'));
            SeoMetaCell::add('og_description', $item->seo_meta->seo_description ?? $item->description ?? get_theme_config('general_seo_description'));
            SeoMetaCell::add('og_url', $item->url);
            SeoMetaCell::add('og_image', $item->images['full']);

            //set breadcrumb
            BreadCrumbCell::add('Home', base_url());
            BreadCrumbCell::add($item->title, $item->url );

            $this->_data['post'] =  $item;
            return $this->_render('blog/post/detail', $this->_data);
        } else {
            return $this->_render('errors/404', $this->_data);
        }

    }

    public function pageDetail($slug)
    {
        $this->_model->join('post_content', 'post_content.post_id = post.id')
            ->where('post_content.lang_id', $this->currentLang->id)
            ->where('post_status', 'publish')
            ->where('slug', $slug);

        $item = $this->_model->first();

        if (isset($item->id)) {
            // SEOData config
            SeoMetaCell::setCanonical($item->url);
            SeoMetaCell::setOgType(SeoMetaEnum::OG_TYPE_ARTCL);
            SeoMetaCell::add('meta_desc', $item->seo_meta->seo_description ?? $item->description ?? get_theme_config('general_seo_description'));
            SeoMetaCell::add('meta_keywords', $item->seo_meta->seo_keyword ?? get_theme_config('general_seo_keyword'));
            SeoMetaCell::add('og_title', $item->seo_meta->seo_title ?? $item->title);
            SeoMetaCell::add('og_site_name', get_theme_config('general_site_title'));
            SeoMetaCell::add('og_description', $item->seo_meta->seo_description ?? $item->description ?? get_theme_config('general_seo_description'));
            SeoMetaCell::add('og_url', $item->url);
            SeoMetaCell::add('og_image', $item->images['full']);

            //set breadcrumb
            BreadCrumbCell::add('Home', base_url());
            BreadCrumbCell::add($item->title, base_url(route_to('page_detail', $item->slug, $item->id)));

            $this->_data['post'] =  $item;
            return $this->_render('blog/post/page', $this->_data);
        } else {
            return $this->_render('errors/404', $this->_data);
        }

    }

    public function author($username)
    {
        return $this->_render('blog/post/author', $this->_data);
    }

    public function tag($tag)
    {
        $tagModel = model(TagsModel::class);
        $item = $tagModel->where('slug', $tag)->first();
        if (isset($item->id)) {

            //SEOData config
            SeoMetaCell::setCanonical(current_url());
            SeoMetaCell::setOgType(SeoMetaEnum::OG_TYPE_ARTCL);
            SeoMetaCell::add('meta_desc', get_theme_config('general_seo_description'));
            SeoMetaCell::add('meta_keywords', get_theme_config('general_seo_keyword'));
            SeoMetaCell::add('og_title', get_theme_config('general_site_title'));
            SeoMetaCell::add('og_site_name', get_theme_config('general_site_title'));
            SeoMetaCell::add('og_description', get_theme_config('general_seo_description'));
            SeoMetaCell::add('og_url', current_url());
            $og_img_data = get_theme_config('general_seo_open_graph_image');
            $ogp_img = isset($og_img_data->full_image) ? $og_img_data->full_image : $this->config->templatePath. '/unigem-logo.png';
            SeoMetaCell::add('og_image', base_url($ogp_img));

            $postTags = $this->_model->join('post_content', 'post_content.post_id = post.id')
                ->where('post_content.lang_id', $this->currentLang->id)
                ->where('post_status', 'publish')
                ->where('post_type', PostTypeEnum::POST)
                ->like('post_content.tags', "%{$item->slug}%")
                ->paginate();

            $this->_data['postTags'] = $postTags;
            $this->_data['pager'] = $this->_model->pager;
            $this->page_title = $item->title;
            return $this->_render('blog/post/tag', $this->_data);
        } else {
            return $this->_render('errors/404', $this->_data);
        }
    }
}