<?php
/**
 * @author tmtuan
 * created Date: 10/09/2021
 * project: fox_cms
 */

namespace App\Models\Blog;

use CodeIgniter\Model;
use Modules\Acp\Entities\Category;
use App\Models\LangModel;

class CategoryModel extends Model
{
    protected $table = 'category';
    protected $primaryKey = 'id';

    protected $returnType = Category::class;
    protected $useSoftDeletes = true;

    protected $allowedFields = [ 'user_init', 'user_type', 'parent_id', 'cat_type', 'cat_status' ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $skipValidation = true;

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

    /**
     * list category by type and lang_id
     *
     * @param  string  $type
     * @param  int  $lang_id
     * @return array
     */
    public function getCategories(string $type, int $lang_id)
    {
        if ( isset($lang_id) && $lang_id > 0 ) $this->where('category_content.lang_id', $lang_id);

        $this->join('category_content', 'category_content.cat_id = category.id', 'LEFT')
            ->where('category.cat_type', $type)
            ->where('category.cat_status', 'publish');

        return $this->findAll();
    }

    /**
     * Check category slug
     * @param $slug
     * @param $langID
     * @return int|string
     */
    public function checkSlug($slug, $langID)
    {
        $builder = $this->db->table($this->table);
        return $builder->join('category_content', 'category_content.cat_id = category.id')
            ->where([
                'category_content.lang_id' => $langID,
                'slug'                     => $slug,
            ])
            ->countAllResults();
    }

    /**
     * Get category by Id
     * @param int $id
     * @param int $lang_id
     * @param string $type
     * @return array|object|null
     */
    public function getById(int $id, int $lang_id, $type = '')
    {
        $builder = $this->join('category_content', 'category_content.cat_id = category.id', 'LEFT')
            ->where('category.cat_status', 'publish')
            ->where('category_content.lang_id', $lang_id);

        if (!empty($type)) {
            $builder->where('category.cat_type', $type);
        }
        return $builder->find($id);
    }
}
