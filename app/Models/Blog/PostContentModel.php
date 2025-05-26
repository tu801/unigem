<?php
/**
 * @author tmtuan
 * created Date: 13-Apr-2025
 */

namespace App\Models\Blog;


use CodeIgniter\Model;

class PostContentModel extends Model
{
    protected $table = 'post_content';
    protected $primaryKey = 'ct_id';

    protected $returnType = 'object';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'post_id', 'lang_id', 'title', 'slug', 'image', 'description', 'content', 'tags', 'seo_meta'
    ];

    protected $useTimestamps = false;

    protected $skipValidation = true;
}