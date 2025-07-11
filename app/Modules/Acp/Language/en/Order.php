<?php
return [
    'page_title'       => 'Order Management',
    'add_title'        => 'Create Order',
    'edit_title'       => 'Process Order',
    'info_basic'       => 'Order Information',
    'customer_name'    => 'Customer Name',
    'phone'            => 'Phone Number',
    'email'            => 'Email',
    'delivery_type'    => 'Delivery Method',
    'shop_id'          => 'Store',
    'title'            => 'Title',
    'note'             => 'Note',
    'status'           => 'Order Status',
    'payment_status'   => 'Payment Status',
    'payment_method'   => 'Payment Method',
    'voucher_code'     => 'Discount Code',
    'product_name'     => 'Product Name',
    'quantity'         => 'Quantity',
    'no_product'       => 'No products yet',
    'add_product'      => 'Add Product',
    'search_product'   => 'Search Product',
    'list_product'     => 'Product List',
    'code'             => 'Order Code',
    'count_product'    => 'Number of Products',
    'total'            => 'Total Amount',
    'total_vnd'        => 'Total Amount (VND)',
    'customer_paid'    => 'Customer Paid',
    'created_view'     => 'Purchase Date',
    'unit_price'       => 'Unit Price',
    'exchange_rate'    => 'Exchange Rate',

    // delivery type
    'delivery_type_1'  => 'Store Pickup',
    'delivery_type_2'  => 'Home Delivery',
    // order status
    'order_status_1'   => 'New Order',
    'order_status_2'   => 'Confirmed',
    'order_status_3'   => 'Processing',
    'order_status_4'   => 'Delivered',
    'order_status_5'   => 'Cancelled',
    'order_status_6'   => 'Completed',
    // payment status
    'payment_status_1' => 'Paid',
    'payment_status_2' => 'Unpaid',
    'payment_status_3' => 'Deposit',
    // payment method
    'payment_method_1' => 'Bank Transfer',
    'payment_method_2' => 'Cash',

    'list_user'        => 'Orders I Created',
    'list_all'         => 'All',
    'list_delete'      => 'Deleted',

    'addSuccess'       => "Successfully added new order #{0}",
    'editSuccess'      => 'Successfully updated order #{0}',

    'addItemToCartSuccess'      => 'Successfully added product to cart',
    'increaseItemQuantity'      => 'Increased product quantity in cart by 1',
    'deleteItemFromCartSuccess' => 'Successfully removed product from cart',

    'sub_total'                 => 'Subtotal',

    'shipping_info'             => 'Shipping Information',
    'ship_full_name'            => 'Recipient Name',
    'ship_telephone'            => 'Recipient Phone',
    'ship_email'                => 'Recipient Email',
    'shipping_fee'              => 'Shipping Fee',
    'discount_amount'           => 'Discount',
    'order_total'               => 'Total',
    'shipping_fee_tooltips'     => 'Shipping fee is calculated by formula: [Item Weight] X [Weight-based Shipping Fee] + [Province-based Shipping Fee]',

    'search_customer'  => 'Search Customer',
    'select_customer'  => 'Select Customer',
    'payment_paid_if_customer_paid' => 'Select paid status when customer pays full amount {0} VND',
    'success_if_payment_paid' => 'Order status completed when order has been paid',
    'currency_exchange_note' => 'Exchange rate is used to calculate order value in the store. All rates are converted to Vietnamese Dong (VND).',

    'voucherAppliedSuccess' => 'Discount code has been applied successfully ',

    // validate
    'full_name_required'      => 'Please enter customer name',
    'phone_required'          => 'Please enter customer phone number',
    'delivery_type_required'  => 'Please select delivery method',
    'status_required'         => 'Please select order status',
    'payment_status_required' => 'Please select payment status',
    'payment_method_required' => 'Please select payment method',
    'product_required'        => 'Please select product',
    'province_id_required'    => 'Please select province/city',
    'district_id_required'    => 'Please select district',
    'ward_id_required'        => 'Please select ward',
    'address_required'        => 'Please enter address',
    'ship_full_name_required' => 'Please enter recipient name',
    'ship_telephone_required' => 'Please enter recipient phone number',

    'create_order_log_desc'   => '#{0} Successfully created order #{1}',

    'invalidVoucherCurrency' => 'Discount code is invalid with the order currency. Please check the discount code or order currency.',

    // invoice
    'invoice_title'           => 'Invoice',
    'invoice_date'            => 'Date',
    'from'                    => 'From',
    'to'                      => 'To',
    'pick_up'                 => 'Store Pickup',
    'order'                   => 'Order',
    'customer_code'           => 'Customer Code',
    'product_sku'             => 'SKU Code',
    'debt'                    => 'Outstanding Debt',
    'apply'                   => 'Apply',
    'discount_code'           => 'Discount Code',
    'deposit_title'           => 'Deposit Information',

    // deposit
    'image_payment'           => 'Payment Image',
    'no_image_payment'        => 'No payment image',
    'no_info'                 => 'No information',
];
