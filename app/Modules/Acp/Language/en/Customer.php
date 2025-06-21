<?php

/**
 * Author: tmtuan
 * Created date: 8/19/2023
 * Project: unigem
 **/

return [
    'page_title'            => 'Customer Management',
    'add_title'             => 'Add Customer',
    'edit_title'            => 'Edit Customer Information',
    'detail_title'          => 'Customer Information',
    'create_customer_title' => 'Create Customer Account',
    'service_information'   => 'Service Information',
    'image_des'             => 'Avatar must be larger than or equal to 200px x 200px',
    'search_placeholder'    => 'Customer code or phone number',
    'walk_in_customer'      => 'Walk-in Customer',

    'avatar'        => 'Avatar',
    'full_name'     => 'Full Name',
    'customer_code' => 'Customer Code',
    'phone'         => 'Phone Number',
    'email'         => 'Email',
    'birthday'      => 'Date of Birth',
    'address'       => 'Address',
    'password'      => 'Password',
    'password_confirm' => 'Password Confirm',

    'add_service'        => 'Add Service',
    'edit_service'       => 'Edit Service',
    'service_code'       => 'Service Code',
    'service_status'     => 'Status',
    'service_created_at' => 'Created Date',
    'service_note'       => 'Note',
    'add_new_service'    => 'Add Service',

    'service_status_new'        => 'New',
    'service_status_inprogress' => 'In Progress',
    'service_status_done'       => 'Completed',

    // success message
    'addSuccess'                => 'Customer #{0} added successfully',
    'addCustomerLog'            => '#{0} added customer #{1}',
    'editCustomerLog'           => '#{0} edited customer information #{1}',
    'editSuccess'               => '#{0} successfully edited customer information #{1}',

    'addServiceSuccess'  => 'New service #{0} added successfully',
    'editServiceSuccess' => 'Service #{0} edited successfully',

    // Form validation
    'full_name_required' => 'Please enter customer full name',
    'phone_required'     => 'Please enter customer phone number',
    'phone_is_unique'    => 'This phone number already exists',
    'cus_phone_min_length'  => 'Phone number is not valid',

    'email_required' => 'Please enter email',
    'valid_email'    => 'Invalid email',
    'email_exits'    => 'This email already exists',

    'customer_id_required' => 'Customer data is incorrect',
    'customer_not_exist'   => 'This customer does not exist',

    'service_id_required'  => 'Please select a service',
    'service_is_not_exist' => 'This service does not exist',

    'cus_address_required'  => 'Please enter the address',

    'password_required'     => 'Please enter password',
    'password_min_length'   => 'Password must be at least 6 characters',
    'password_confirm_matches_password' => 'Password does not match',

    // From edit modal
    'enable_warranty'                  => 'Service Warranty',
    'guarantee_time'                   => 'Warranty Period',
    'guarantee_start'                  => 'Start',
    'guarantee_end'                    => 'End',
    'note'                             => 'Note',
    'status'                           => 'Status',
    'status_1'                         => 'Active',
    'status_0'                         => 'Inactive',
    'title_customer_service_guarantee' => 'Warranty History List',
    'add_service_guarantee'            => 'Warranty Note',
    'doctor'                           => 'Doctor',
    'addServiceGuaranteeSuccess'       => 'Warranty Note Added Successfully',
    'addServiceGuaranteeLog'           => '#{0} Provided Warranty #{1}',
    'doctor_name_required'             => 'Please enter the Doctor\'s Name',

    // check guarantee
    'not_guarantee'          => 'No Warranty',
    'month'                  => 'Month',
    'check_guarantee_title'  => 'Warranty Lookup',
    'check_guarantee_button' => 'Check',
    'under_warranty'         => 'Under Warranty',
    'not_warranty'           => 'No Warranty',
    'no_warranty'            => 'Not Yet Warranted',
    'phone_support'          => 'Support Hotline',
    'date_buy'               => 'Purchase Date',
    'edit_profile'           => 'Edit Profile',
];
