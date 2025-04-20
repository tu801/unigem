<?php
/**
 * @author tmtuan
 * created Date: 10/4/2023
 * project: Unigem
 */

namespace App\Enums;


class UploadFolderEnum extends BaseEnum
{
    /**
     * Attach folder upload
     */
    const ATTACH = 'attach';

    /**
     * Post folder upload
     */
    const POST = 'post';

    /**
     * product
     */
    const PRODUCT = 'product';
    
    /**
     * Shop folder upload
     */
    const SHOP = 'shop';

    /**
     * Promotion image upload
     */
    const PROMOTION = 'promotion';
}