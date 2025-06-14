<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('404.html', 'Home::error_404', ['as' => 'show_error']);
$routes->match(['GET', 'POST'], 'contact.html', 'Home::contactUs', ['as' => 'contactUs']);

/**
 * Product
 */
$routes->group('product', ['namespace' => '\App\Controllers\Product'], function ($routes) {
    $routes->get('', 'Product::list', ['as' => 'product_shop']);
    $routes->get('search', 'Product::list', ['as' => 'product_search']);

    $routes->get('([a-zA-Z0-9_-]+)', 'Category::list/$1', ['as' => 'product_category']);
    $routes->get('([a-zA-Z0-9_-]+)-(:num).html', 'Product::detail/$1', ['as' => 'product_detail']);
});

/**
 * Customer
 */
$routes->group('customer', ['namespace' => '\App\Controllers\Customer'], function ($routes) {
    $routes->get('profile', 'Profile::profile', ['as' => 'cus_profile']);
    $routes->match(['GET', 'POST'], 'account-profile-info', 'Profile::profileInfo', ['as' => 'edit_cus_profile']);
    $routes->match(['GET', 'POST'], 'change-password', 'AuthCustomer::changePassword', ['as' => 'cus_change_password']);

    $routes->get('order-history', 'OrderHistory::listOrder', ['as' => 'order_history']);
    $routes->get('order-history/(:num)', 'OrderHistory::detail/$1', ['as' => 'order_history_detail']);

    
    $routes->match(['GET', 'POST'], 'my-voucher/claim-gift/(:num)', 'Voucher::claimGift/$1', ['as' => 'claim_gift']);

    // Authentication
    $routes->get('login', '\App\Controllers\Login::login', ['as' => 'cus_login']);
    $routes->post('login', '\App\Controllers\Login::actionLogin');
    $routes->get('register', 'Register::register', ['as' => 'cus_register']);
    $routes->post('register', 'Register::registerSubmit');
    $routes->get('logout', 'AuthCustomer::logout', ['as' => 'cus_logout']);

    // Activation
    $routes->get('activate-account', 'AuthCustomer::activateAccount', ['as' => 'cus_activate_account']);
    $routes->post('activate/verify', 'AuthCustomer::verify', ['as' => 'cus_activate_account_verify']);
    $routes->get('forgot-password', 'AuthCustomer::forgotPassword', ['as' => 'cus_forgot_password']);
});

/**
 * Order
 */
$routes->group('order', ['namespace' => '\App\Controllers\Order'], function ($routes) {
    $routes->get('cart', 'Order::cart', ['as' => 'order_cart']);
    $routes->get('checkout', 'Order::checkout', ['as' => 'order_checkout']);
    $routes->post('checkout', 'Order::actionCheckout');
    $routes->get('success/([a-zA-Z0-9_-]+)', 'Order::orderSuccess/$1', ['as' => 'order_success']);
    $routes->get('payment/([a-zA-Z0-9_-]+)', 'Order::orderPayment/$1', ['as' => 'order_payment']);
    $routes->post('payment/([a-zA-Z0-9_-]+)', 'Order::actionOrderPayment/$1');
    $routes->get('get-product', 'Order::getProduct');
    $routes->get('apply-voucher', 'Order::applyVoucher');
});

// ajax routes
$routes->group('ajax', ['namespace' => '\Modules\Ajax\Controllers'], function ($routes) {
    $routes->post('subscribe-email', 'ContactController::addSubscribeEmail');

    $routes->get('get-province', 'AreaController::getProvinces');
    $routes->get('get-district/(:num)', 'AreaController::getDistricts/$1');
    $routes->get('get-ward/(:num)', 'AreaController::getWards/$1');
    $routes->get('get-shipping-fee', 'AreaController::getShippingFee');

    $routes->post('customer-login', 'CustomerController::login');
    $routes->get('customer-logout', 'CustomerController::logout');

    $routes->group('product', null, function ($routes) {
        $routes->get('get-product/(:num)', 'ProductController::getProductById/$1');
        $routes->post('search', 'AjaxController::searchProduct');
    });
});

/**
 * blog routes
 */
$routes->get('([a-zA-Z0-9_-]+)', '\App\Controllers\Blog\Category::list/$1', ['as' => 'category_page']);
$routes->get('([a-zA-Z0-9_-]+).html', '\App\Controllers\Blog\Post::detail/$1', ['as' => 'post_detail']);
$routes->get('page/([a-zA-Z0-9_-]+)-(:num)', '\App\Controllers\Blog\Post::pageDetail/$1', ['as' => 'page_detail']);
$routes->get('author/([a-zA-Z0-9_-]+)', '\App\Controllers\Blog\Post::author/$1', ['as' => 'post_author']);
$routes->get('tag/([a-zA-Z0-9_-]+)', '\App\Controllers\Blog\Post::tag/$1', ['as' => 'post_tag']);
