<?php

/**
 * @author tmtuan
 * created Date: 10/23/2023
 * Project: Unigem
 */

namespace App\Models\Store\Product;


use CodeIgniter\Model;
use App\Entities\Store\Product;

class ProductModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'product';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = Product::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields = [
        'user_init',
        'cat_id',
        'pd_sku',
        'pd_image',
        'pd_status',
        'minimum',
        'publish_date',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Fields to select when querying products for order or cart
     * @var array
     */
    public $productQueryFields = [
        'id',
        'cat_id',
        'pd_sku',
        'pd_image',
        'pd_status',
        'product.created_at',
        'product.updated_at',
        'deleted_at',
        'product_content.lang_id',
        'product_content.pd_name',
        'product_content.pd_slug',
        'product_content.pd_weight',
        'product_content.pd_size',
        'product_content.pd_cut_angle',
        'product_content.origin_price',
        'product_content.price',
        'product_content.price_discount',
        'product_content.pd_tags',
        // 'product_content.pd_description',
        // 'product_content.product_info',
        'product_content.seo_meta',
    ];
}