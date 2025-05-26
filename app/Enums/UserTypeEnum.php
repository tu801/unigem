<?php
/**
 * Author: tmtuan
 * Created date: 13-Apr-2025
 **/

namespace App\Enums;


class UserTypeEnum extends BaseEnum
{
    /**
     * Default user type
     */
    const USER = 'user';

    /**
     * Admin user type who can access admin panel
     */
    const ADMIN = 'admin';

    /**
     * Customer user type
     */
    const CUSTOMER = 'customer';
}