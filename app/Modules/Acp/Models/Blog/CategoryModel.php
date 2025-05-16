<?php

/**
 * @author tmtuan
 * created Date: 10/09/2021
 * project: fox_cms
 */

namespace Modules\Acp\Models\Blog;

use App\Models\Blog\CategoryContentModel;
use App\Models\Blog\CategoryModel as BaseCategoryModel;
use App\Models\LangModel;

class CategoryModel extends BaseCategoryModel
{
    /**
     * insert new item if exist otherwise edit the item data
     * @param $input
     */
    public function insertOrUpdate($input)
    {
        $_catContent = model(CategoryContentModel::class);

        if (isset($input['id']) && $catID = $input['id']) {
            $this->update($catID, $input);
            $_catContent->where('cat_id', $catID)
                ->where('lang_id', $input['cur_lang_id'])
                ->set($input)
                ->update();
            return true;
        } else {
            $cat             = $this->insert($input);
            $input['cat_id'] = $cat;

            $langData = model(LangModel::class)->listLang();
            try {
                foreach ($langData as $item) {
                    $input['lang_id'] = $item->id;
                    $_catContent->insert($input);
                }
            } catch (\Exception $e) {
                log_message('error', $e->getMessage());
            }
            return $cat;
        }
    }
}
