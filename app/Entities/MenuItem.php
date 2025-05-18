<?php
/**
 * Author: tmtuan
 * Created date: 11/9/2023
 * Project: Unigem
 **/

namespace App\Entities;


use CodeIgniter\Entity\Entity;
use App\Enums\CategoryEnum;
use App\Models\Blog\CategoryModel;
use App\Models\Blog\PostModel;

class MenuItem extends Entity
{
    const CAT_TYPE = 'category';
    const PAGE_TYPE = 'page';
    const URL_TYPE = 'url';

    /**
     * Define properties that are automatically converted to Time instances.
     */
    protected $dates = ['created_at', 'updated_at'];

    protected $display_url;

    public function getDisplayUrl()
    {
        if (empty($this->id)) {
            throw new \RuntimeException(lang('Acp.invalid_entity'));
        }
        $lang = session()->lang;

        switch ($this->attributes['type']) {
            case self::URL_TYPE:
            case self::PAGE_TYPE:
                $page = model(PostModel::class)->getById($this->attributes['related_id'], $lang->id, $this->attributes['type']);

                if ( !isset($page->id) ) {
                    $this->display_url = $this->attributes['url'];
                } else {
                    $this->display_url = base_url(route_to('page_detail', $page->slug, $page->id));
                }
                break;
            case self::CAT_TYPE:
                $catData = model(CategoryModel::class)
                    ->getById($this->attributes['related_id'], $lang->id);

                switch ($catData->cat_type) {
                    case CategoryEnum::CAT_TYPE_PRODUCT:
                        $catUrl = base_url(route_to('product_category', $catData->slug));
                        break;
                    case CategoryEnum::CAT_TYPE_POST:
                    default:
                        $catUrl = base_url(route_to('category_page', $catData->slug));
                        break;
                }
                $this->display_url = $catUrl;
                break;

        }

        return $this->display_url;
    }
}