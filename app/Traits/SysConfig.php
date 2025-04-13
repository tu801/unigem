<?php
/**
 * @author tmtuan
 * created Date: 04/11/2025
 */
namespace App\Traits;

use App\Models\ConfigModel;

trait SysConfig {

    /**
     * get System Config Data from database or cache
     */
    public function getConfig() {

        $modelConfig = model(ConfigModel::class);
        $cfData = $modelConfig->findAll();
        $configs = [];
        foreach ($cfData as $item){
            $key = trim($item->group_id).'_'.$item->key;
            if ( !array_key_exists($key, $configs) ) {
                $configs[$key] = (isset($item->is_json) && $item->is_json == 1)? json_decode($item->value) : $item->value;
            }
        }
        $this->config->sys = $configs;

        // set max size for image if this config is exist
        if ( isset($configs['default_max_size']) && $configs['default_max_size'] > 0 ) {
            $this->maxSizeImage = $configs['default_max_size'] * 1024;
        }
    }
}