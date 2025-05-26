<?php
/**
 * @author tmtuan
 * created Date: 10/23/2023
 * Project: Unigem
 */

namespace Modules\Acp\Models\Store\Product;

use App\Models\LangModel;
use App\Models\Store\Product\ProductContentModel as BaseProductContentModel;

class ProductContentModel extends BaseProductContentModel
{
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