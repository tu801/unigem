<?php
/**
 * @author tmtuan
 * created Date: 10/23/2023
 * Project: Unigem
 */

namespace Modules\Acp\Models\Store\Product;


use CodeIgniter\Model;
use Modules\Acp\Models\LangModel;

class ProductContentModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'product_content';
    protected $primaryKey       = 'pd_ct_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'product_id',
        'lang_id',
        'pd_name',
        'pd_slug', 
        'pd_weight',
        'pd_size',
        'pd_cut_angle',
        'origin_price',
        'price',
        'price_discount',
        'pd_tags',
        'pd_description',
        'product_info',
        'seo_meta'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

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

    protected array $seoMetaData = [
        'seo_title',
        'seo_keyword',
        'seo_description',
    ];

    /**
     * create product content for each language
     * @param int $productID
     * @param array $data
     * @return void
     */
    public function createProductContent($productID, $data) {
        if ( empty($data) || empty($productID)  ) {
            throw new \CodeIgniter\Shield\Models\DatabaseException('Data is empty');
        }
        $langData = model(LangModel::class)->listLang();
        $seoMeta = []; 
        foreach ($this->seoMetaData as $key) {
            if (!empty($data[$key])) {
                $seoMeta[$key] = $data[$key];
            }
        }
        $data['product_id'] = $productID;
        $data['seo_meta'] = json_encode($seoMeta);
        if ( !empty($data['tagcloud']) ) $data['pd_tags'] = json_encode($data['tagcloud']);
        
        try {
            foreach ($langData as $langItem) {
                $checkProductItem = $this->where('product_id', $productID)->where('lang_id', $langItem->id)->get()->getFirstRow();
                if ( !isset($checkProductItem->id)) {
                    $data['lang_id'] = $langItem->id;
                    $this->insert($data);
                }
            }
        } catch (\Exception $e) {
            log_message('error', $e->getMessage());
            throw new \CodeIgniter\Shield\Models\DatabaseException('Create product content failed!');
        }
    }
}