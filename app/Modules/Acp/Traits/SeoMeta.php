<?php
/**
 * @author tmtuan
 * created Date: 10-Dec-20
 */

namespace Modules\Acp\Traits;

use Modules\Acp\Models\Blog\MetaDataModel;

trait SeoMeta {
    /**
     * get current post Meta data
     *
     * @return array|bool
     */
    public function getSeoMeta() {
        if (empty($this->id)) {
            throw new \RuntimeException('Users must be created before getting meta data.');
        }
        $mod_name = $this->metaModName??'post';
        if ( empty($this->seometa) ) {
            $meta = model(MetaDataModel::class)->where('mod_name', $mod_name)
                    ->where('mod_id', $this->id)
                    ->where('meta_key', 'seo_meta')
                    ->get()->getFirstRow();
            if ( !empty($meta) ) $this->seometa = json_decode($meta->meta_value);
            else {
                $data = json_encode(['meta_keywords' => null, 'meta_description' => null]);
                $this->seometa = json_decode($data);
            }
        }

        return $this->seometa;
    }

    /**
     * Save SEO Meta
     * @param $input
     */
    public function setSeoMeta($input) {
        if (empty($this->id)) {
            throw new \RuntimeException('Users must be created before getting meta data.');
        }
        $mod_name = $this->metaModName??'post';
        if ( !empty($input['meta_keywords']) && !empty($input['meta_description']) ) {
            $metaVal = [
                'meta_keywords' => $input['meta_keywords'],
                'meta_description' => $input['meta_description']
            ];
            $meta = model(MetaDataModel::class)->updateMeta($mod_name,$this->id, 'seo_meta', $metaVal);
            if ( !empty($meta) ) $this->seometa = json_decode($meta->meta_value);
        }
    }
}