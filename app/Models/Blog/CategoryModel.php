<?php
/**
 * @author tmtuan
 * created Date: 13-Apr-2025
 */

namespace App\Models\Blog;

use CodeIgniter\Model;
use App\Entities\Category;

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
     * list category by type and lang_id
     *
     * @param  string  $type
     * @param  int  $lang_id
     * @return array
     */
    public function getCategories(string $type, ?int $lang_id)
    {
        if ( isset($lang_id) && $lang_id > 0 ) $this->where('category_content.lang_id', $lang_id);

        $this->join('category_content', 'category_content.cat_id = category.id', 'LEFT')
            ->where('category.cat_type', $type)
            ->where('category.cat_status', 'publish');

        return $this->findAll();
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
