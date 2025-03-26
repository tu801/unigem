<?php
/**
 * @author tmtuan
 * created Date: 10/09/2021
 * project: fox_cms
 */

namespace Modules\Acp\Models\Blog;

use CodeIgniter\Model;
use Modules\Acp\Models\Store\Product\ProductModel;

class TagsModel extends Model
{
    protected $table = 'tags';
    protected $primaryKey = 'id';

    protected $returnType = 'object';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'title', 'slug', 'tag_type', 'user_init', 'user_type'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $skipValidation = true;

    public function getTagByPost($postId, $langID){

        $sql = "SELECT tags FROM `post_content` WHERE `post_content`.`post_id` = {$postId} AND `lang_id` = {$langID}";
        $query = $this->db->query($sql);
        if ( $query ) return $query->getFirstRow('object');
        else return false;
    }

    public function getTagByProduct($id)
    {
        $tblPrefix = $this->db->getPrefix();
        $builder = $this->db->table("{$tblPrefix}product");
//        $data = \model(ProductModel::class)
//            ->select('pd_tags')
//            ->find($id);
        $data = $builder
            ->select('pd_tags')
            ->where('id', $id)
            ->get()->getFirstRow('object');
        return $data ?? false;
    }
}