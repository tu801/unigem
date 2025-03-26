<?php
/**
 * @author tmtuan
 * created Date: 18-Oct-20
 */

namespace Modules\Acp\Entities;

use CodeIgniter\Config\Services;
use CodeIgniter\Entity\Entity;
use Modules\Acp\Models\AttachMetaModel;
use Modules\Acp\Models\AttachModel;
use Modules\Acp\Models\Blog\CategoryContentModel;
use Modules\Acp\Models\Blog\MetaDataModel;

class Category extends Entity {

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
     * get the attached image
     * @return array|bool|mixed|void
     */
    public function getAttachFile() {
        if (empty($this->id)) {
            throw new \RuntimeException(lang('Cat.entity_error_request'));
        }

        if ( empty($this->attach_file) ) {
            $this->attach_file = (new AttachMetaModel())->getAttMeta($this->id, 'category');
        }
        return $this->attach_file;
    }

    /**
     * save the attach image file to DB
     * @param $postData
     * @return bool
     */
    public function setAttachFile($postData) {
        if (empty($this->id)) {
            throw new \RuntimeException(lang('Cat.entity_error_request'));
        }

        if ( isset($postData['meta_id']) && $postData['meta_id'] > 0) {
            $result = (new AttachMetaModel())->updateMeta($postData, $postData['meta_id']);
        } else {
            $postData['att_meta_id'] = $this->id;
            $result = (new AttachModel())->attachFiles($postData);
        }
        return $result;
    }

    public function getSeoMeta() {
        if (empty($this->id)) {
            throw new \RuntimeException(lang('Cat.entity_error_request'));
        }

        if (empty($this->seo_meta)) { 
            $session = Services::session();
            $meta = model(CategoryContentModel::class)->where([
                    'cat_id'  => $this->id,
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
}