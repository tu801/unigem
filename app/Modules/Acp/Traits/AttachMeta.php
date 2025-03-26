<?php
/**
 * @author tmtuan
 * created Date: 08-Apr-21
 */

namespace Modules\Acp\Traits;

trait AttachMeta {
    public function setAttachFiles($postData) {
        if ( empty($postData['att_meta_type']) || empty($postData['att_meta_name'])
        || empty($postData['att_meta_id']) || empty($postData['att_meta_img'])) return false;
        else {

        }
    }
}