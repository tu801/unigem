<?php
return [
    'product_title'           => 'Product Management',
    'title_add'               => 'Add Product',
    'title_edit'              => 'Edit Product',
    'no_item_found'           => 'Product not found',

    // add product page
    'pd_name'                 => 'Product Name',
    'pd_sku'                  => 'SKU Code',
    'pd_slug'                 => 'Slug',
    'image'                   => 'Avatar',
    'avatar_size'             => 'Avatar must be at least 620 x 620 pixels',
    'pd_tags'                 => 'Tags',
    'pd_origin_price'         => 'Original Price',
    'pd_price'                => 'Sale Price',
    'pd_price_discount'       => 'Discount',
    'pd_price_discount_tooltip' => 'If there is a discount amount, the system will use this number for calculation when ordering. If the discount amount is 0, the system will use the sale price.',
    'pd_status'               => 'Status',
    'minimum'                 => 'Minimum',
    'weight'                  => 'Weight',
    'size'                    => 'Size',
    'cut_angle'               => 'Cut Angle',
    'description'             => 'Short Description',
    'product_info'            => 'Product Information',
    'pd_category'             => 'Product Category',
    'pd_image_other'          => 'Other Product Images',
    'list_user_product'       => 'My Products',
    'list_all_product'        => 'All',
    'list_delete_product'     => 'Deleted',

    // add product validate
    'cat_id_required'         => 'Please select a product category',
    'pd_name_required'        => 'Please enter the product name',
    'pd_name_min_length'      => 'Product name must be at least 3 characters',
    'pd_name_is_unique'       => 'Product name already exists',
    'pd_sku_required'         => 'Please enter the SKU code',
    'pd_image_required'       => 'Please select a product avatar',
    'origin_price_required'   => 'Please enter the original price',
    'price_required'          => 'Please enter the sale price',
    'price_discount_required' => 'Please enter the discount',
    'pd_status_required'      => 'Please select the product status',
    'minimum_required'        => 'Please select the minimum purchase quantity',
    'weight_required'         => 'Please enter the product weight',
    'description _required'   => 'Please enter a short description of the product',

    // product status
    'status_3'                => 'Draft',
    'status_2'                => 'Pending Approval',
    'status_1'                => 'Published',
    'status_4'                => 'Out of Stock',

    // message
    'addSuccess'              => "Successfully added new product #{0}",
    'editSuccess'             => 'Successfully updated product #{0}',
    'product_must_be_created' => 'Product must be created before getting meta data',
    'pd_name_is_not_create_slug' => 'Cannot create slug from this product name',
];