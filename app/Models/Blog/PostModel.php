<?php
/**
 * @author tmtuan
 * created Date: 10/09/2021
 * project: fox_cms
 */

namespace App\Models\Blog;

use CodeIgniter\Config\Services;
use CodeIgniter\Model;
use App\Entities\Post;

class PostModel extends Model
{
    protected $table = 'post';
    protected $primaryKey = 'id';

    protected $returnType = Post::class;
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'user_init', 'user_type', 'post_status', 'post_type', 'post_views', 'post_position', 'publish_date'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $skipValidation = true;

    //get post category
    public function getCat($postId, string $type) {
        $type = $type ?? 'post';
        $tblPrefix = $this->db->getPrefix();
        $session = Services::session();

        $catModel = new CategoryModel();
        $catData = $catModel->select('category.*, post_categories.is_primary,category_content.*')
                ->join($tblPrefix.'post_categories', "{$tblPrefix}category.id = {$tblPrefix}post_categories.cat_id", 'LEFT')
                ->join('category_content', 'category_content.cat_id = category.id')
                ->where('cat_type', $type)
                ->where('lang_id', $session->lang->id)
                ->where("{$tblPrefix}post_categories.post_id = {$postId}");

        return $catData->get()->getResultArray();
    }

}
