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
use App\Enums\Post\PostStatusEnum;
use App\Enums\UploadFolderEnum;
use CodeIgniter\I18n\Time;

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

    /**
     * Get post by id
     *
     * @param int $id
     * @param int $lang_id
     * @param string $post_type
     * @return post object
     */
    public function getById(int $id, int $lang_id, $post_type = '')
    {
        $builder = $this->join('post_content', 'post_content.post_id = post.id')
            ->where('post_content.lang_id', $lang_id)
            ->where('post_status', PostStatusEnum::PUBLISH)
            ->where('post.post_type', $post_type);

        return $builder->find($id);
    }

    /**
     * Delete post and related data such as post content, post category, post image
     * If isPermanentDelete = true, delete post permanently
     *
     * @param $post
     * @param $isPermanentDelete
     * @return bool
     */
    public function deletePost($post, $isPermanentDelete) {
        if ( !$isPermanentDelete ) {
            return $this->delete($post->id);
        }

        //delete post content
        $postContentModel = model(PostContentModel::class);
        $postContentData = $postContentModel->where('post_id', $post->id)->findAll();

        $myTime = Time::parse($post->created_at);
        $postSubFolder = UploadFolderEnum::POST . '/' . $myTime->format( 'Y/m');
        if ($postContentData) {
            foreach ($postContentData as $value) {
                $postContentModel->delete($value->ct_id);

                delete_image($value->image, $postSubFolder);
            }
        }
        //delete post category
        $postCategoryBuilder = $this->db->table('post_categories');
        $postCategoryBuilder->where('post_id', $post->id);
        $postCategoryBuilder->delete();

        return $this->delete($post->id, true);
    }
}
