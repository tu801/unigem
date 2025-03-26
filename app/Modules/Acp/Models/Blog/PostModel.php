<?php
/**
 * @author tmtuan
 * created Date: 10/09/2021
 * project: fox_cms
 */

namespace Modules\Acp\Models\Blog;

use CodeIgniter\Config\Services;
use CodeIgniter\Model;
use Modules\Acp\Entities\Post;
use Modules\Acp\Models\LangModel;

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


    /**
     * Recover soft delete post
     */
    public function recover($id) {
        $sql = "UPDATE `tmt_post` SET `deleted_at` = NULL WHERE `tmt_post`.`id` = {$id}";
        if ( $this->db->query($sql) ) return true;
        else return false;
    }

    /**
     * get All post in Month
     */
    public function postInMonth($month, $status = ''){
        $tblPrefix = $this->db->getPrefix();
        $stt = ( isset($status) && $status !== '' ) ? "AND post_status = '{$status}' " : '';
        $sql = " SELECT * FROM `{$tblPrefix}post` WHERE post_type = 'post' AND MONTH(created_at) = {$month} {$stt} AND deleted_at IS NULL ORDER BY id DESC ";

        $result = $this->db->query($sql);
        return $result->getCustomResultObject(Post::class);
    }

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
     * Remove all categories by post item id
     * @param int $postId
     */
    public function removeCatById(int $postId) {
        $tblPrefix = $this->db->getPrefix();
        $builder = $this->db->table($tblPrefix.'post_categories');
        $builder->where('post_id', $postId);
        $builder->delete();
    }

    /**
     * Insert post to categories
     * @param $postId
     * @param $catId
     */
    public function addCategories($postId, $catId, $is_primary = 0){
        $tblPrefix = $this->db->getPrefix();
        $builder = $this->db->table($tblPrefix.'post_categories');
        $insertData = [
            'post_id' => $postId,
            'cat_id' => $catId,
            'is_primary' => $is_primary
        ];
        $builder->insert($insertData);
    }

    /**
     * check post title exist
     * @param $title
     * @return bool
     */
    public function checkTitle($title)
    {
        $_contentModel = \model(PostContentModel::class);
        $post = $_contentModel->where('title', $title)->first();
        if ( !isset($post->id) || empty($post) ) return true;
        else return false;
    }

    /**
     * Add new post data
     * @param $post
     * @return bool|\CodeIgniter\Database\BaseResult|false|int|object|string
     * @throws \ReflectionException
     */
    public function addPost($post)
    {
        $newPostId = $this->insert($post);
        if ( $newPostId ) {
            $_contentModel = \model(PostContentModel::class);
            $langData = \model(LangModel::class)->listLang();
            try {
                foreach ($langData as $item) {
                    $post->post_id = $newPostId;
                    $post->lang_id = $item->id;

                    if ( !isset($post->seo_title) || empty($post->seo_title) ) $post->seo_title = $post->title;
                    if ( !isset($post->seo_description) || empty($post->seo_description) ) $post->seo_description = $post->description;

                    $_contentModel->insert($post);
                }
            } catch (\Exception $e) {
                log_message('error', $e->getMessage());
            }

            return $newPostId;
        } else return false;

    }

    /**
     * @param $slug
     * @param $langID
     * @return int|string
     */
    public function checkSlug($slug, $langID)
    {
        $builder = $this->db->table($this->table);
        return $builder->join('post_content', 'post_content.post_id = post.id')
            ->where([
                'post_content.lang_id' => $langID,
                'slug'                     => $slug,
            ])
            ->countAllResults();
    }

}
