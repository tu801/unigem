<?php
/**
 * @author tmtuan
 * @github https://github.com/tu801
 * created Date: 10/24/2023
 */

namespace App\Enums;


class CategoryEnum extends BaseEnum {

    /**
    *  Category type
    */
    const CAT_TYPE_POST = 'post';
    const CAT_TYPE_PRODUCT = 'product';

    /**
     * Category Status
     */
    const CAT_STATUS_PUBLISH = 'publish';

    /**
     * Category attachment prefix key in table attach_meta
     */
    const CAT_ATTACHMENT_PREFIX_KEY = 'cat_image_';
}