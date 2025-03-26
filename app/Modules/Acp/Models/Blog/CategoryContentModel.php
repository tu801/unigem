<?php
/**
 * @author tmtuan
 * created Date: 10/23/2021
 * project: fox_cms
 */
namespace Modules\Acp\Models\Blog;

use CodeIgniter\Model;
use PhpParser\Node\Expr\Cast\Object_;

class CategoryContentModel extends Model
{
    protected $table = 'category_content';
    protected $primaryKey = 'id';

    protected $returnType = 'object';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'cat_id', 'lang_id', 'title', 'slug', 'description', 'seo_meta'
    ];

    protected $useTimestamps = false;

    protected $skipValidation = true;

}
