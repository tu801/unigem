<?php
return [
    // Actions
    'user_title'            => 'User Management',
    'title_edit'            => 'Edit User Information',
    'title_add'             => 'Add New User',
    'search'                => 'Search',
    'edit_permission_title' => 'Edit User Permissions',
    'edit_permission_group_title' => 'Edit Group Permissions',
    'edit_password'         => 'Change Password',

    // Profile Info
    'title_info'            => 'Information',
    'title_general_info'    => 'General Information',

    // Add User
    'title_account_info'    => 'Account Information',
    'title_user_info'       => 'Personal Information',
    'addSuccess'            => 'Successfully added new user #{0}',

    // Edit User
    'edit_title'            => 'Edit User Information',
    'editSuccess'           => 'Successfully updated information for user #{0}',

    // User Info
    'username'              => 'Username',
    'email'                 => 'Email',
    'phone'                 => 'Phone Number',
    'address'               => 'Address',
    'birthday'              => 'Birthday',
    'password'              => 'Password',
    'repassword'            => 'Confirm Password',
    'full_name'             => 'Full Name',
    'avata'                 => 'Avatar',
    'user_group'            => 'User Group',
    'created'               => 'Created Date',

    // User Meta
    'user_meta_title'       => 'Additional Information',

    // User Group Controller
    'userGroup_title'       => 'Group Management',
    'userGroup_add_title'   => 'Add Group',
    'userGroup_name'        => 'Group Name',
    'userGroup_description' => 'Group Description',

    // Errors
    'invalid_user'          => 'This account does not exist! Please try again',
    'invalid_user_group'    => 'This group does not exist! Please try again',

    'username_required'     => 'Please enter a username',
    'alpha_numeric_space'   => 'Username can only contain letters, numbers, and spaces',
    'min_length'            => 'Username must have at least 4 characters',
    'user_is_exist'         => 'This username already exists',
    'email_required'        => 'Please enter your email',
    'valid_email'           => 'Please enter a valid email format',
    'email_exits'           => 'This email already exists',
    'fullname_required'     => 'Please enter your full name',
    'pw_required'           => 'Please enter a password',
    'pw_length'             => 'Password must have at least 4 characters',
    'pwcf_required'         => 'Please enter password confirmation',
    'pwcf_matches'          => 'Passwords do not match',
    'invalid_avata'         => 'Avatar must be in [jpg/jpeg/png/gif] format and less than 1Mb in size',
    'maxsize_avata'         => 'Avatar must be less than 1Mb in size',

    'invalid_delete_user'   => "You can't delete the currently logged-in user",
    'delete_success'        => 'Successfully deleted user ID [{0, number}]',
    'delete_fail'           => 'Delete command not executed. Please try again later',
    'recover_fail'          => 'Recovery command not executed. Please try again later',

    'userGroupName_required'=> 'Please enter a group name',
    'userGroupName_is_exist'=> 'This group name already exists',
    'save_group_permission_success' => 'Successfully assigned permissions for group #{0}',
    'save_user_permission_success' => 'Successfully assigned permissions for user #{0}',
    'edit_pass_success'     => 'Password change successful',
    'recover_success'       => 'Successfully recovered user',
    'activate_success'      => 'Successfully activated user',
];
