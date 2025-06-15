<?php
/**
 * Author: tmtuan
 * Created date: 11/19/2023
 * Project: Unigem
 **/

namespace App\Enums\Store;


/**
 * customer active status for table
 * customer.active
 * if customer had user account the user.active status will be the same as customer.active
 * Class CustomerActiveEnum
 * @package App\Enums\Store
 */
class CustomerActiveEnum
{
    const ACTIVE = 1;
    const INACTIVE = 0;

    const FORGOT_PASSWORD_TYPE_EMAIL = 'email_forgot_password';
}