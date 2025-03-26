<?php
/**
 * Author: tmtuan
 * Created date: 8/19/2023
 * Project: nha-khoa-viet-my
 **/

namespace Modules\Acp\Enums;


/**
 * Contain the post_type for each post
 * Class PostTypeEnum
 * @package Modules\Acp\Enums
 */
class PostTypeEnum extends BaseEnum
{
    /**
     * Blog post
     */
    const POST = 'post';

    /**
     * Page post
     */
    const PAGE = 'page';
}