<?php

/**
 * @author tmtuan
 * created Date: 18-Oct-20
 */

namespace App\Entities;

use App\Enums\CategoryEnum;
use CodeIgniter\Config\Services;
use CodeIgniter\Entity\Entity;
use App\Models\AttachMetaModel;
use App\Models\AttachModel;
use App\Models\Blog\CategoryContentModel;
use CodeIgniter\I18n\Time;

class Category extends Entity
{

    /**
     * Define properties that are automatically converted to Time instances.
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * Category SEO meta cache
     * @var array
     */
    protected $seo_meta = [];

    /**
     * Category custom image attach
     * @var array
     */
    protected $attach_file = [];

    /**
     * Category image used for display in Home page
     */
    protected $cat_image;

    protected $url;

    /**
     * get the category url
     * @return string
     */
    public function getUrl()
    {
        if (empty($this->id)) {
            throw new \RuntimeException(lang('Cat.entity_error_request'));
        }

        if (empty($this->url)) {
            // Kiểm tra slug hợp lệ (chỉ chứa a-z, 0-9, dấu gạch ngang, không bắt đầu/kết thúc bằng dấu gạch ngang)
            $slug = $this->attributes['slug'] ?? '';
            if (!preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug)) {
                $this->url = '#';
                return $this->url;
            }

            switch ($this->attributes['cat_type']) {
                case CategoryEnum::CAT_TYPE_PRODUCT:
                    $this->url = base_url(route_to('product_category', $this->attributes['slug']));
                    break;
                default:
                    $this->url = base_url(route_to('category_page', $this->attributes['slug']));
                    break;
            }
        }

        return $this->url;
    }

    /**
     * get the attached image
     * @return array|bool|mixed|void
     */
    public function getAttachFile()
    {
        if (empty($this->id)) {
            throw new \RuntimeException(lang('Cat.entity_error_request'));
        }

        if (empty($this->attach_file)) {
            $this->attach_file = (new AttachMetaModel())->getAttMeta($this->id, 'category');
        }
        return $this->attach_file;
    }

    /**
     * save the attach image file to DB
     * @param $postData
     * @return bool
     */
    public function setAttachFile($postData)
    {
        if (empty($this->id)) {
            throw new \RuntimeException(lang('Cat.entity_error_request'));
        }

        if (isset($postData['meta_id']) && $postData['meta_id'] > 0) {
            $result = (new AttachMetaModel())->updateMeta($postData, $postData['meta_id']);
        } else {
            $postData['att_meta_id'] = $this->id;
            $result = (new AttachModel())->attachFiles($postData);
        }
        return $result;
    }

    public function getSeoMeta()
    {
        if (empty($this->id)) {
            throw new \RuntimeException(lang('Cat.entity_error_request'));
        }

        if (empty($this->seo_meta)) {
            $session = Services::session();
            $meta = model(CategoryContentModel::class)->where([
                'cat_id'  => $this->id,
                'lang_id' => $session->lang->id
            ])->first();

            if (!empty($meta->seo_meta)) {
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
            throw new \RuntimeException(lang('Cat.entity_error_request'));
        }

        if (!empty($input['seo_keyword']) || !empty($input['seo_description']) || !empty($input['seo_title'])) {
            $metaVal = [
                'seo_keyword'     => $input['seo_keyword'] ?? '',
                'seo_description' => $input['seo_description'] ?? '',
                'seo_title'       => $input['seo_title'] ?? '',
            ];
            model(CategoryContentModel::class)->where([
                'cat_id'  => $this->id,
                'lang_id' => $input['lang_id']
            ])->set([
                'seo_meta' => json_encode($metaVal)
            ])->update();
        }
    }

    public function getCatImage()
    {
        if (empty($this->id)) {
            throw new \RuntimeException(lang('Cat.entity_error_request'));
        }
        $config = config('Site');
        $noImgFileName = 'cate' . rand(1, 9) . '.jpg';
        $imageUrl = base_url($config->templatePath . '/images/shop/cate/' . $noImgFileName);

        $session = Services::session();
        $key = CategoryEnum::CAT_ATTACHMENT_PREFIX_KEY . $session->lang->locale;
        $metaAttach = model(AttachMetaModel::class);
        $attachModel = model(AttachModel::class);

        $attachMeta = $metaAttach->where('mod_name', $key)->where('mod_id', $this->id)->first();

        if ($attachMeta) {
            $images = json_decode($attachMeta->images);

            $attach = $attachModel->find($images->image);
            if ($attach) {
                $mytime = Time::parse($attach->created_at);
                $attachFile = 'uploads/attach/' . $mytime->format('Y/m') . '/' . $attach->file_name;
                // check file exists in path
                if (file_exists(FCPATH . str_replace('/', DIRECTORY_SEPARATOR, $attachFile))) {
                    $imageUrl = base_url($attachFile);
                }

                return [
                    'image'          => $imageUrl,
                    'image_id'       => $images->image,
                    'attach_meta_id' => $attachMeta->id,
                ];
            }
        }

        return [
            'image'          => $imageUrl
        ];
    }
}
