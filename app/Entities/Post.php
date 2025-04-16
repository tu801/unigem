<?php
/**
 * @author tmtuan
 * created Date: 13-Oct-20
 */

namespace App\Entities;

use App\Models\User\UserModel;
use CodeIgniter\Config\Services;
use CodeIgniter\Entity\Entity;
use CodeIgniter\I18n\Time;
use App\Enums\Post\PostTypeEnum;
use App\Models\Blog\CategoryModel;
use App\Models\Blog\MetaDataModel;
use App\Models\Blog\PostCategoryModel;
use App\Models\Blog\PostContentModel;
use App\Models\Blog\PostModel;
use Modules\Acp\Traits\SeoMeta;

class Post extends Entity {
    use SeoMeta;
    /**
     * Define properties that are automatically converted to Time instances.
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'publish_date'];

    /**
     * Post categories
     * @var array
     */
    protected $categories = [];

    /**
     * Post meta data
     * @var array
     */
    protected $seo_meta = [];

    /**
     * Post author data
     * @var array
     */
    protected $author = [];

    /**
     * Custom Post Meta Data
     * @var array
     */
    protected $customMeta = [];

    /**
     * images url
     * @var array
     */
    protected $images = [];

    /**
     * @var string - post detail url
     */
    protected $url;

    /**
     * @var object - list post categories
     */
    protected $priv_cat;


    public $metaModName = 'post';

    public function getAuthor() {
        if ( isset($this->attributes['user_init']) && $this->attributes['user_init'] > 0 ) {
            $this->attributes['author'] = (model(UserModel::class))
                                        ->select('id, username, avatar, created_at')
                                        ->find($this->attributes['user_init']);
        } else $this->attributes['author'] = null;
        return $this->attributes['author'];
    }

    public function getCategories() {
        if (empty($this->id))
        {
            throw new \RuntimeException('Post must be created before getting data.');
        }
        if (empty($this->categories)){
            $this->categories = (new PostModel())->getCat($this->attributes['id'], $this->attributes['post_type']);
        }

        return $this->categories;
    }

    /**
     * get post images url
     * @return array
     * @throws \Exception
     */
    public function getImages() {
        if (empty($this->id)) {
            throw new \RuntimeException('Users must be created before getting meta data.');
        }
        $config = config('Acp');
        if (  !empty($this->attributes['image']) ) {
            $mytime = Time::parse($this->attributes['created_at']);
            $this->images = [
                'full' => base_url("uploads/post/".$mytime->format('Y/m').'/'.$this->attributes['image']),
                'thumbnail' => base_url("uploads/post/".$mytime->format('Y/m').'/thumb/'.$this->attributes['image'])
            ];

        } else {
            $this->images = [
                'full' => base_url($config->noimg),
                'thumbnail' => base_url($config->noimg),
            ];
        }
        return $this->images;
    }

    public function getSeoMeta() {
        if (empty($this->id)) {
            throw new \RuntimeException(lang('Post.entity_error_request'));
        }

        if (empty($this->seo_meta)) {
            $session = Services::session();
            $meta = model(PostContentModel::class)->where([
                'post_id'  => $this->id,
                'lang_id' => $session->lang->id
            ])->first();

            if ( !empty($meta->seo_meta) ) {
                $this->seo_meta = json_decode($meta->seo_meta);
            } else {
                $data = json_encode(['seo_keyword' => null, 'seo_description' => null, 'seo_title' => null]);
                $this->seo_meta = json_decode($data);
            }
        }
        return $this->seo_meta;
    }

    public function setSeoMeta($input)
    {
        if (empty($this->id)) {
            throw new \RuntimeException('Post must be created before getting meta data.');
        }

        if (!empty($input['seo_keyword']) && !empty($input['seo_description']) && !empty($input['seo_title'])) {
            $metaVal = [
                'seo_keyword'     => $input['seo_keyword'],
                'seo_description' => $input['seo_description'],
                'seo_title'       => $input['seo_title']
            ];
            model(PostContentModel::class)->where([
                'post_id'  => $this->id,
                'lang_id' => $input['lang_id']
            ])->set([
                'seo_meta' => json_encode($metaVal)
            ])->update();
        }
    }

    /**
     * return post detail url
     * @return string
     */
    public function getUrl() {
        if (empty($this->id)) {
            throw new \RuntimeException('Post must be created before getting meta data.');
        }
        $postContent = model(PostContentModel::class)
            ->where('post_id', $this->attributes['id'])
            ->first();

        switch ($this->attributes['post_type']) {
            case PostTypeEnum::POST:
                $this->url = base_url( route_to('post_detail', $postContent->slug, $this->id) );
                break;
            case PostTypeEnum::PAGE:
                $this->url = base_url( route_to('page_detail', $postContent->slug, $this->id) );
                break;
            default:
                $this->url = base_url($postContent->slug.'-'.$this->id);
                break;
        }

        return $this->url;
    }

    /**
     * save custom metadata
     * @param $input
     */
    public function saveCustomMeta($input) {
        if (empty($this->id)) {
            throw new \RuntimeException('Post must be created before getting meta data.');
        }
        $metaModel = new MetaDataModel();
        $cf = config('Acp');
        $metaCf = $cf->postCf['custom'][$this->attributes['post_type']]['meta'];

        foreach ($metaCf as $metaKey => $metaVal) {
            if ( isset($input[$metaKey]) && !empty($input[$metaKey]) ) {
                $meta = $metaModel->updateMeta('post', $this->id, $metaKey, $input[$metaKey]);
                if ( !empty($meta) ) $this->customMeta[$metaKey] = json_decode($meta->meta_value);
            }
        }
    }

    /**
     * get custom post Meta data
     *
     * @return array|bool
     */
    public function getCustomMeta() {
        if (empty($this->id)) {
            throw new \RuntimeException('Post must be created before getting meta data.');
        }
        $metaModel = new MetaDataModel();
        $cf = config('Acp');
        $metaCf = $cf->postCf['custom'][$this->attributes['post_type']]['meta'];

        if ( empty($this->customMeta) ) {
            foreach ($metaCf as $key => $val) {
                $meta = $metaModel->where('mod_name', 'post')
                        ->where('mod_id', $this->id)
                        ->where('meta_key', $key)
                        ->get()->getFirstRow();
                if ( !array_key_exists($key, $this->customMeta) && !empty($meta) ) $this->customMeta[$key] = json_decode($meta->meta_value);
            }
        }
        return $this->customMeta;
    }

    /**
     * get private category
     * @return object|bool
     */
    public function getPrivCat() {
        if (empty($this->id)) {
            throw new \RuntimeException('Post must be created before getting meta data.');
        } 
        $session = Services::session();
        $priv_cat = model(PostCategoryModel::class)->queryPostPrivCat($this->id, $session->lang->id);
        $priv_cat->url = base_url(route_to('category_page', $priv_cat->slug));
        $this->priv_cat = $priv_cat;
        return $this->priv_cat;
    }

}