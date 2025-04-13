<?php
/**
 * Author: tmtuan
 * Created date: 8/19/2023
 * Project: unigem
 **/

namespace App\Enums;


/**
 * Contain the post_type for each post
 * Class PostTypeEnum
 * @package App\Enums
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