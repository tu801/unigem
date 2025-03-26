<?php
/**
 * @author tmtuan
 * created Date: 10/23/2023
 * Project: Unigem
 */

namespace Modules\Acp\Models\Store\Product;


use CodeIgniter\Model;

class ProductMetaModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'product_content';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'product_id',
        'lang_id',
        'pd_name', 'pd_slug', 'pd_weight', 'pd_size', 'pd_tags' , 'pd_description', 'product_info', 'seo_meta'
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

    protected array $metaKey = [
        'seo_title',
        'seo_keyword',
        'seo_description',
    ];

    public function insertOrUpdate($data)
    {
        if (!isset($data['product_id']) || !isset($data['meta_key']) || !isset($data['meta_value'])) {
            return fasle;
        }
        $productID = $data['product_id'];
        $metaKey   = $data['meta_key'];
        $metaValue = $data['meta_value'];

        $builder = $this->db->table($this->table);

        $item = $builder->where('product_id', $productID)
            ->where('meta_key', $metaKey)
            ->get()
            ->getFirstRow();

        if (isset($item->meta_id)) {
            $builder->set('meta_value', $metaValue)
                ->where('meta_id', $item->meta_id)
                ->update();
            return $builder->where('meta_id', $item->meta_id)->get()->getFirstRow();

        } else {
            $insertData = [
                'product_id' => $productID,
                'meta_key'   => $metaKey,
                'meta_value' => $metaValue,
            ];
            $rsId       = $this->insert($insertData);
            return $builder->where('meta_id', $rsId)->get()->getFirstRow();
        }
    }

    public function saveCustomMeta($productID, $data)
    {
        foreach ($this->metaKey as $key) {
            if (!empty($data[$key])) {
                $this->insertOrUpdate([
                    'product_id' => $productID ,
                    'meta_key'   => $key,
                    'meta_value' => $data[$key],
                ]);
            }
        }
    }
}