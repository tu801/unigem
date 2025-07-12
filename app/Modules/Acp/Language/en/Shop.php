<?php

/**
 * @author tmtuan
 * @github https://github.com/tu801
 * created Date: 05/17/2025
 */

return [
    'page_title'            => 'Store Management',
    'add_title'             => 'Add Store',
    'edit_title'            => 'Edit Store Information',

    'image'                 => 'Avatar',
    'name'                  => 'Store Name',
    'phone'                 => 'Phone Number',
    'address'               => 'Address',

    'status_active'         => 'Active',
    'status_in_active'      => 'Inactive',

    // error message
    'name_required'         => 'Please enter the store name',
    'name_is_exist'         => 'Store name already exists',
    'phone_required'        => 'Please enter the phone number',

    // success message
    'addSuccess'            => 'Successfully created store #{0}',
    'editSuccess'           => 'Successfully updated information for store #{0}',

    // Exchange Rate Page
    'exchange_rate_manager'             => 'Exchange Rate Management',

    'note'                              => 'Note',
    'currency_exchange_info'            => 'All exchange rates are converted to Vietnamese Dong (VND) and used to calculate the value of orders in the store.',

    'currency_from'                     => 'Foreign currency to convert',
    'currency_to'                       => 'Exchange rate (VND)',
    'current_exchange_rate'             => 'Current exchange rate',
    'last_update'                       => 'Last updated',

    'save_exchange_rate'                => 'Save exchange rate',

    'rate_required'                     => 'Please enter the exchange rate',
    'rate_decimal'                      => 'Exchange rate must be a valid number',
    'add_exchange_rate_log_title'       => 'Add exchange rate from {0} to {1}',
    'add_exchange_rate_log_desc'        => 'User {0} has added a new exchange rate from {1} to {2}.',
    'addRateSuccess'                    => 'Exchange rate has been successfully added [{0}]',
    'editRateSuccess'                   => 'Exchange rate has been successfully updated [{0}]',


    // Voucher Management Page
    'voucher_page_title'                => 'Voucher Management',
    'add_voucher_title'                 => 'Add Voucher',
    'edit_voucher_title'                => 'Edit Voucher',

    'voucher_code'                      => 'Voucher Code',
    'voucher_code_info'                 => 'Voucher code must be unique and cannot contain spaces. Example: "DISCOUNT2025". If no code is assigned, the system will automatically generate a random voucher code.',
    'voucher_title'                     => 'Voucher Title',
    'voucher_description'               => 'Voucher Description',
    'voucher_discount'                  => 'Discount',
    'voucher_time'                      => 'Usage Time',
    'voucher_status'                    => 'Status',
    'voucher_status_0'                  => 'Unused',
    'voucher_status_1'                  => 'Active',
    'voucher_status_2'                  => 'Expired',
    'voucher_discount_type'             => 'Discount Type',
    'voucher_discount_type_percentage'  => 'Percentage Discount',
    'voucher_discount_type_fixed_amount' => 'Fixed Amount Discount',
    'voucher_discount_value'            => 'Value',
    'voucher_start_date'                => 'Start Date',
    'voucher_end_date'                  => 'End Date',
    'voucher_currency_list'             => 'Currency Type',

    'voucher_code_is_exist'             => 'Voucher code already exists, please choose another code.',
    'voucher_title_required'            => 'Please enter voucher title.',
    'voucher_discount_type_required'    => 'Please select discount type.',
    'voucher_discount_value_required'   => 'Please enter discount value.',
    'voucher_code_not_found'            => 'Voucher code does not exist or has expired.',
    'voucher_code_required'             => 'Please enter voucher code.',
];