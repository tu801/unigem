<?php

/**
 * @author tmtuan
 * created Date: 13-Apr-2025
 */

namespace App\Models\Blog;

use CodeIgniter\Model;
use App\Entities\Category;
use App\Enums\CacheKeys;

class CategoryModel extends Model
{
    protected $table = 'category';
    protected $primaryKey = 'id';

    protected $returnType = Category::class;
    protected $useSoftDeletes = true;

    protected $allowedFields = ['user_init', 'user_type', 'parent_id', 'cat_type', 'cat_status'];

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
    public function getCategories(string $type, ?int $lang_id = null)
    {
        $cacheKey = CacheKeys::CATEGORY_PREFIX . 'get_' . $type . '_' . $lang_id;

        $catData = cache()->get($cacheKey);
        if ($catData !== null) {
            return $catData;
        }

        if (isset($lang_id) && $lang_id > 0) $this->where('category_content.lang_id', $lang_id);
        else {
            $lang = session()->lang;
            $this->where('category_content.lang_id', $lang->id);
        }

        $catData = $this->join('category_content', 'category_content.cat_id = category.id', 'LEFT')
            ->where('category.cat_type', $type)
            ->where('category.cat_status', 'publish')
            ->findAll();

        cache()->save($cacheKey, $catData, config('Cache')->ttl);

        return $catData;
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
        $cacheKey = CacheKeys::CATEGORY_PREFIX . $id. '_'. $lang_id. '_'. $type;

        $catData = cache()->get($cacheKey);
        if ($catData !== null) {
            return $catData;
        }
        $builder = $this->join('category_content', 'category_content.cat_id = category.id', 'LEFT')
            ->where('category.cat_status', 'publish')
            ->where('category_content.lang_id', $lang_id);

        if (!empty($type)) {
            $builder->where('category.cat_type', $type);
        }
        $catData = $builder->find($id);

        cache()->save($cacheKey, $catData, config('Cache')->ttl); 

        return $catData;
    }


    /**
     * Lấy danh sách product category kèm số lượng sản phẩm
     * 
     * @return array
     */
    public function getProductCategoriesWithProductCount(int $lang_id)
    {
        $cacheKey = CacheKeys::CATEGORY_PREFIX . 'product_with_count_'. $lang_id;

        $catData = cache()->get($cacheKey);
        if ($catData!== null) {
            return $catData;
        }

        $catData = $this->select('category.id, category.cat_type, category_content.title, category_content.slug, COUNT(product.id) as count')
            ->join('category_content', 'category_content.cat_id = category.id', 'LEFT')
            ->join('product', 'product.cat_id = category.id', 'LEFT')
            ->where('category.cat_type', 'product')
            ->where('category.cat_status', 'publish')
            ->where('category_content.lang_id', $lang_id)
            ->groupBy('category.id, category_content.title, category_content.slug')
            ->findAll();
        cache()->save($cacheKey, $catData, config('Cache')->ttl);

        return $catData;
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
}
