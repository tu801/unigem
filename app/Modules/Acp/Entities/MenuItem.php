<?php
/**
 * Author: tmtuan
 * Created date: 11/9/2023
 * Project: Unigem
 **/

namespace Modules\Acp\Entities;


use CodeIgniter\Entity\Entity;
use Modules\Acp\Enums\CategoryEnum;
use Modules\Acp\Models\Blog\CategoryModel;

class MenuItem extends Entity
{
    const CAT_TYPE = 'category';
    const PAGE_TYPE = 'page';
    const URL_TYPE = 'url';

    /**
     * Define properties that are automatically converted to Time instances.
     */
    protected $dates = ['created_at', 'updated_at'];

    public function getUrl()
    {
        if (empty($this->id)) {
            throw new \RuntimeException(lang('Acp.invalid_entity'));
        }
        $lang = session()->lang;

        switch ($this->attributes['type']) {
            case self::URL_TYPE:
            case self::PAGE_TYPE:
                return $this->attributes['url'];
                break;
            case self::CAT_TYPE:
                $catData = model(CategoryModel::class)
                    ->getById($this->attributes['related_id'], $lang->id);

                switch ($catData->cat_type) {
                    case CategoryEnum::CAT_TYPE_PRODUCT:
                        $catUrl = base_url('product/'.$catData->slug);
                        break;
                    case CategoryEnum::CAT_TYPE_POST:
                    default:
                        $catUrl = base_url($catData->slug);
                        break;
                }
                return $catUrl;
                break;

        }
    }
}