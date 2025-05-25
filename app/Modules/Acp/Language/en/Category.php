<?php
return [
    //title
    'category_title'            => 'Category',
    'add_category'              => 'Add Category',
    'edit_title'                => 'Edit Category Information',
    'cat_image'                 => 'Category image must have a minimum size of 720x600px',

    'search'                    => 'Search Category',

    //category field
    'title'                     => 'Title',
    'slug'                      => 'Slug',
    'slug_desc'                 => 'The slug is the URL-friendly version of the category name. It usually includes all lowercase letters, and only contains letters, numbers, and hyphens.',
    'parent_cat'                => 'Parent Category',
    'cat_status'                => 'Status',
    'description'               => 'Description',
    'change_slug'               => 'Change the slug according to the title when saving',

    //Error
    'invalid_request'   => 'Invalid request! Please try again later.',
    'title_required'    => 'Please enter a title for the category.',
    'alpha_numeric_space'   => 'The title can only contain letters and numbers.',
    'title_is_exist'    => 'This category already exists.',
    'min_length'        => 'The title must be at least 3 characters long.',
    'slug_is_exist'     => 'This URL already exists! Please choose a different URL for the category.',

    'addSuccess'        => 'Successfully added a new category.',
    'delete_success'    => 'Successfully deleted category #[{0, number}]',

    'slug_required'                 => 'Please enter a URL for the category',
    'slug_min_length'               => 'URL must be at least 3 characters',
    'slug_invalid'                  => 'Slug URL can only contain letters, numbers, and hyphens',
    'slug_is_invalid_text'          => 'Invalid slug URL. It can only contain letters, numbers, and hyphens. Please check your slug again.',
    'updateSlug_success'            => 'Successfully updated the new URL',
    'error_delete_cat_has_child'    => 'You cannot delete a category that has subcategories. Please delete all subcategories first.',
    'cat_parent_not_found'          => 'Parent category not found',
];