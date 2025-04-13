<?php
/**
 * @author tmtuan
 * created Date: 13-Apr-2025
 */
namespace App\Models\Blog;

use CodeIgniter\Model;

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
