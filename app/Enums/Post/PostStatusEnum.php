<?php
/**
 * @author tmtuan
 * created Date: 04/13/2025
 */

namespace App\Enums\Post;

use App\Enums\BaseEnum;

class PostStatusEnum extends BaseEnum
{
    const DRAFT = 'draft';
    const PUBLISH = 'publish';
    const PENDING = 'pending';
}