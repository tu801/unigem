<?php
/**
 * @author tmtuan
 * created Date: 10/25/2023
 * Project: Unigem
 */

namespace App\Validations;


class PhoneValidation
{
    /**
     * Check if a number is Vietnamese valid phone number
     * @param $value
     * @param string|null $error
     * @return bool
     */
    public function valid_phone($value, ?string &$error = null): bool
    {
        if (!preg_match('/(84|0[3|5|7|8|9])+([0-9]{8})/', $value)) {
            $error = lang('Validate.invalid_phone_number');

            return false;
        }

        return true;
    }
}