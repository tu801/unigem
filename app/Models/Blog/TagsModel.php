<?php
/**
 * @author tmtuan
 * created Date: 10/09/2021
 * project: fox_cms
 */

namespace App\Models\Blog;

use CodeIgniter\Model;
use App\Models\Store\Product\ProductModel;

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

    public function getTagByProduct($productId, $langID)
    {
        $productModel = model(ProductModel::class);
        $data = $productModel
            ->join('product_content', 'product_content.product_id = product.id')
            ->select('product_content.pd_tags')
            ->where('id', $productId)
            ->where('product_content.lang_id', $langID)
            ->get()->getFirstRow('object');

        return $data ?? false;
    }
}