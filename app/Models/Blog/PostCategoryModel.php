<?php
/**
 * @author tmtuan
 * created Date: 04/13/2025
 */

namespace App\Models\Blog;

use CodeIgniter\Model;

class PostCategoryModel extends Model {
    protected $table = 'post_categories';
    protected $primaryKey = 'id';
    protected $allowedFields = ['cat_id', 'post_id', 'is_primary'];
    protected $returnType = 'object';

    protected $useTimestamps = false;

    public function queryPostPrivCat(int $post_id, int $lang_id) {
        $this->select('category.*, category_content.title, category_content.slug, category_content.description, category_content.seo_meta')
            ->join('category', 'category.id = post_categories.cat_id')
            ->join('category_content', 'category_content.cat_id = category.id')
            ->where('post_categories.post_id', $post_id)
            ->where('post_categories.is_primary', 1)
            ->where('category_content.lang_id', $lang_id);

        return $this->first();
    }
}