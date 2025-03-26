<?php
/**
 * Author: tmtuan
 * Created date: 9/26/2023
 * Project: thuthuatonline
 **/

namespace Modules\Acp\Enums;


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