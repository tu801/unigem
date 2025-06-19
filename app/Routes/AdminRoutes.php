<?php

/**
 * Modules:Acp routes file.
 */

$routes->group('acp', ['namespace' => 'Modules\Acp\Controllers'], function ($routes) {
    //handle db migration
    $routes->group('dbMigrate', ['namespace' => 'Modules\Acp\Controllers\System', 'filter' => 'group:superadmin'], function ($routes) {
        $routes->get('/', 'Migrate::index');
    });

    //dashboard controller
    $routes->get('dashboard', 'Dashboard::index', ['as' => 'dashboard']);

    //attach controller
    $routes->group('attach', ['namespace' => 'Modules\Acp\Controllers\System'], function ($routes) {
        $routes->post('mceUpload', 'Attach::ajxTinyMceUpl');
        $routes->get('get_upload_data', 'Attach::getUploadData');
        $routes->post('upload/', 'Attach::ajxGalleryUpload');
        $routes->get('ajxdel/(:num)', 'Attach::ajxDeleteItem/$1');
    });

    //user controller
    $routes->group('user', ['namespace' => 'Modules\Acp\Controllers\User'], function ($routes) {
        $routes->get('profile', 'User::profile');
        $routes->get('edit-password/(:num)', 'User::editPassword/$1', ['as' => 'edit_password']);
        $routes->post('edit-password/(:num)', 'User::attempEditPassword/$1');

        $routes->match(['GET', 'POST'], '/', 'User::index', ['as' => 'list_user', 'filter' => 'group:superadmin,admin']);

        $routes->get('search/', 'User::ajxSearchUser');

        $routes->get('recover/(:num)', 'User::recover/$1', ['as' => 'recover_user', 'filter' => 'group:superadmin,admin']);
        $routes->get('ativate/(:num)', 'User::active/$1', ['as' => 'active_user', 'filter' => 'group:superadmin,admin']);

        $routes->get('add', 'User::add', ['as' => 'add_user', 'filter' => 'group:superadmin,admin']);
        $routes->post('add', 'User::addAction', ['filter' => 'group:superadmin,admin']);

        $routes->get('edit/(:num)', 'User::edit/$1', ['as' => 'edit_user', 'filter' => 'group:superadmin,admin']);
        $routes->post('edit/(:num)', 'User::editAction/$1', ['filter' => 'group:superadmin,admin']);

        $routes->get('remove/(:num)', 'User::remove/$1', ['as' => 'remove_user', 'filter' => 'group:superadmin,admin']);
    });

    //config controller
    $routes->group('config', ['namespace' => 'Modules\Acp\Controllers\System\Config', 'filter' => 'group:superadmin,admin'], function ($routes) {
        $routes->get('/', 'Config::index', ['as' => 'config']);
        $routes->post('/', 'Config::index');
        $routes->get('add/(:alpha)', 'Config::add/$1', ['as' => 'add_config']);
        $routes->post('add/(:alpha)', 'Config::addAction/$1');

        $routes->get('edit/(:num)', 'Config::edit/$1', ['as' => 'edit_config']);
        $routes->post('edit/(:num)', 'Config::editAction/$1');

        $routes->get('clone/(:num)', 'Config::clone/$1', ['as' => 'clone_config']);

        $routes->get('delete/(:num)', 'Config::remove/$1', ['as' => 'delete_config']);
        $routes->get('custom/([a-zA-Z0-9_-]+)', 'Config::custom/$1', ['as' => 'config_custom']);
        $routes->post('custom/([a-zA-Z0-9_-]+)', 'Config::customAction/$1');

        $routes->get('get-custom-attach/', 'Config::getCustomAttachFile/$1');

        $routes->get('clear-cache', static function () {
            echo command('cache:clear');
        }, ['as' => 'clear_cache']);
    });

    $routes->group('theme-option', ['namespace' => 'Modules\Acp\Controllers\System\Config', 'filter' => 'group:superadmin,admin'], function ($routes) {
        $routes->get('/', 'ThemeOptionController::index', ['as' => 'theme-option']);
        $routes->post('/', 'ThemeOptionController::saveOptions');

        $routes->post('create-slider', 'ThemeOptionController::createSlider', ['as' => 'create-slider']);
        $routes->post('save-slider', 'ThemeOptionController::saveSlider', ['as' => 'save-slider']);
        $routes->get('get-slider', 'ThemeOptionController::getSlider');
        $routes->post('delete-slider', 'ThemeOptionController::deleteSlider');
    });

    //log controller
    $routes->group('log', ['namespace' => 'Modules\Acp\Controllers\System', 'filter' => 'group:superadmin,admin'], function ($routes) {
        $routes->get('/', 'Log::index', ['as' => 'sys_log']);
        $routes->get('lst-sys-act', 'Log::ajaxLstSysAct');
        $routes->get('lst-sys-login', 'Log::ajaxLstSysLogin');
        $routes->get('mod-sys-act', 'Log::ajaxModSysAct');
        $routes->get('detail/(:num)', 'Log::detail/$1', ['as' => 'detail_sys_act_log']);
    });

    // record type routes
    $routes->group('record-type', ['namespace' => 'Modules\Acp\Controllers\System'], function ($routes) {
        $routes->get('/', 'RecordTypeController::index', ['as' => 'recordType']);

        $routes->get('list-data', 'RecordTypeController::vlist_data');
        $routes->post('edit/(:num)', 'RecordTypeController::ajaxEdit/$1');
        $routes->post('add', 'RecordTypeController::ajaxAdd');
        $routes->post('del-item', 'RecordTypeController::ajxRemove');
    });

    // menu routes
    $routes->group('menu', ['namespace' => 'Modules\Acp\Controllers\Blog', 'filter' => 'group:superadmin,admin,content_manager'], function ($routes) {
        $routes->get('/', 'MenuController::index', ['as' => 'menu']);
        $routes->get('edit/(:num)', 'MenuController::edit/$1', ['as' => 'edit_menu']);

        $routes->post('add_url', 'MenuController::addUrlAjx', ['as' => 'add_url_item']);
        $routes->get('add_item/(:num)', 'MenuController::addMenuItem/$1', ['as' => 'add_menu_item']);
        $routes->get('add-page-item/(:num)', 'MenuController::addPageItem/$1', ['as' => 'add_page_item']);

        $routes->post('save-menu', 'MenuController::saveMenu', ['as' => 'save_menu']);
        $routes->post('del-menu', 'MenuController::ajxRemove');

        $routes->get('remove/(:num)', 'MenuController::removeItem/$1', ['as' => 'remove_menu_item']);

        //menu ajax
        $routes->get('list-menu', 'MenuController::ajxListMenu');
        $routes->get('list-page-menu', 'MenuController::listPageMenuAjx');
        $routes->post('ajx-add-menu', 'MenuController::addMenuAjx', ['as' => 'ajx_add_menu']);
        $routes->post('edit-menu/(:num)', 'MenuController::editAjx/$1');
    });

    //category routes
    $routes->group('category', ['namespace' => 'Modules\Acp\Controllers\Blog', 'filter' => 'group:superadmin,admin,content_manager,sale_manager'], function ($routes) {
        $routes->get('(:alpha)', 'Category::index/$1', ['as' => 'category']);
        $routes->post('(:alpha)', 'Category::index/$1');

        $routes->get('add/(:alpha)', 'Category::add/$1', ['as' => 'add_category']);
        $routes->post('add/(:alpha)', 'Category::addAction/$1');

        $routes->get('edit/(:num)', 'Category::edit/$1', ['as' => 'edit_category']);
        $routes->post('edit/(:num)', 'Category::editAction/$1');

        $routes->get('remove/(:num)', 'Category::removeCat/$1', ['as' => 'remove_category']);
        $routes->get('recover/(:num)', 'Category::recoverCat/$1', ['as' => 'recover_category']);

        //Ajax
        $routes->get('list-cat/(:alpha)', 'Category::vuejsListCat/$1');
        $routes->post('ajxEditSlug/(:num)', 'Category::ajxEditSlug/$1');
    });

    //post routes
    $routes->group('post', ['namespace' => 'Modules\Acp\Controllers\Blog', 'filter' => 'group:superadmin,admin,content_manager'], function ($routes) {
        $routes->get('/', 'Post::index', ['as' => 'post']);
        $routes->post('/', 'Post::index');
        $routes->get('add', 'Post::add', ['as' => 'add_post']);
        $routes->post('add', 'Post::addAction');
        $routes->get('edit/(:num)', 'Post::edit/$1', ['as' => 'edit_post']);
        $routes->post('edit/(:num)', 'Post::editAction/$1');
        $routes->post('ajxEditSlug/(:num)', 'Post::ajxEditSlug/$1');

        $routes->post('delete', 'Post::ajxRemove', ['as' => 'delete_post']);
        $routes->post('permanent-delete-post', 'Post::permanentDeletePost', ['as' => 'permanent_delete_post']);
        $routes->get('recover/(:num)', 'Post::recover/$1', ['as' => 'recover_post']);

        $routes->get('ajxreview/(:num)', 'Post::ajxreview/$1', ['as' => 'ajxreview_post']);
        $routes->get('ajx-get-post/(:num)', 'Post::ajxGetPost/$1');

        $routes->get('get-post-by-url', 'Post::crawlPost', ['as' => 'crawl_post']);
        $routes->post('get-post-by-url', 'Post::crawlPostAction');
    });

    //Tags routes
    $routes->group('tags', ['namespace' => 'Modules\Acp\Controllers\Blog'], function ($routes) {
        $routes->get('get-data', 'Tags::getData');
        $routes->post('add-tag', 'Tags::ajxAddTags');
        $routes->get('get-post-tags/(:num)', 'Tags::getPostTagById/$1');
        $routes->get('get-tags/(:num)/(:alpha)', 'Tags::getTags/$1/$2');
    });

    //page routes
    $routes->group('page', ['namespace' => 'Modules\Acp\Controllers\Blog', 'filter' => 'group:superadmin,admin,content_manager'], function ($routes) {
        $routes->get('/', 'Page::index', ['as' => 'page']);
        $routes->post('/', 'Page::index');

        $routes->get('add', 'Page::addPage', ['as' => 'add_page']);
        $routes->post('add', 'Page::addPageAction');

        $routes->get('edit/(:num)', 'Page::editPage/$1', ['as' => 'edit_page']);
        $routes->post('edit/(:num)', 'Page::editPageAction/$1');

        $routes->post('permanent-delete-page', 'Page::permanentDeletePage', ['as' => 'permanent_delete_page']);
        $routes->post('remove', 'Page::ajxRemove/$1', ['as' => 'remove_page']);
    });

    //product routes
    $routes->group('product', ['namespace' => 'Modules\Acp\Controllers\Store\Product', 'filter' => 'group:superadmin,admin,sale_manager'], function ($routes) {
        $routes->get('/', 'ProductController::index', ['as' => 'product']);

        $routes->get('add', 'ProductController::addProduct', ['as' => 'add_product']);
        $routes->post('add', 'ProductController::addProductAction');

        $routes->get('edit/(:num)', 'ProductController::editProduct/$1', ['as' => 'edit_product']);
        $routes->post('edit/(:num)', 'ProductController::editProductAction/$1');

        $routes->post('remove', 'ProductController::ajxRemove/$1', ['as' => 'remove_product']);
        $routes->get('recover/(:num)', 'ProductController::recover/$1', ['as' => 'recover_product']);
        $routes->post('search-product', 'ProductController::ajaxSearchProduct', ['as' => 'search_product']);
    });

    // shop routes
    $routes->group('shop', ['namespace' => 'Modules\Acp\Controllers\Store', 'filter' => 'group:superadmin,admin,sale_manager'], function ($routes) {
        $routes->match(['GET', 'POST'], '/', 'ShopController::index', ['as' => 'list_shop']);
        $routes->match(['GET', 'POST'], 'add', 'ShopController::addShop', ['as' => 'add_shop']);
        $routes->match(['GET', 'POST'], 'edit/(:num)', 'ShopController::editShop/$1', ['as' => 'edit_shop']);
        $routes->post('remove', 'ShopController::ajxRemove/$1', ['as' => 'remove_shop']);
    });

    // contact routes
    $routes->group('contact', ['namespace' => 'Modules\Acp\Controllers\Store', 'filter' => 'group:superadmin,admin,sale_manager'], function ($routes) {
        $routes->match(['get', 'post'], '/', 'ContactController::index', ['as' => 'list_contact']);
        $routes->get('view/(:num)', 'ContactController::viewContact/$1', ['as' => 'view_contact']);
        $routes->post('view/(:num)', 'ContactController::editContact/$1');
    });

    // ajax routes
    $routes->group('ajax', ['namespace' => 'Modules\Acp\Controllers\Store'], function ($routes) {
        $routes->get('get-province', 'AjaxController::getProvinces', ['as' => 'get_provinces']);
        $routes->get('get-district/(:num)', 'AjaxController::getDistricts/$1', ['as' => 'get_districts']);
        $routes->get('get-ward/(:num)', 'AjaxController::getWards/$1', ['as' => 'get_wards']);
        $routes->get('get-banks', 'AjaxController::getBanks', ['as' => 'get_banks']);
        $routes->get('get-shipping-fee', 'AjaxController::getShippingFee', ['as' => 'get_shipping_fee']);
    });

    // customer routes
    $routes->group('customer', ['namespace' => 'Modules\Acp\Controllers\Store\Customer', 'filter' => 'group:superadmin,admin,sale_manager'], function ($routes) {
        $routes->get('/', 'CustomerController::index', ['as' => 'customer']);
        $routes->post('/', 'CustomerController::index');

        $routes->get('show/(:num)', 'CustomerController::detail/$1', ['as' => 'customer_detail']);

        $routes->get('add', 'CustomerController::addCustomer', ['as' => 'add_customer']);
        $routes->post('add', 'CustomerController::addAction');

        $routes->get('ativate/(:num)', 'CustomerController::active/$1', ['as' => 'active_customer']);

        $routes->get('edit/(:num)', 'CustomerController::editCustomer/$1', ['as' => 'edit_customer']);
        $routes->post('edit/(:num)', 'CustomerController::editAction/$1');
        $routes->post('remove', 'CustomerController::ajxRemove/$1', ['as' => 'remove_customer']);
        $routes->post('search-customer', 'CustomerController::ajaxSearchCustomer', ['as' => 'search_customer']);
        $routes->get('create-customer-account/(:num)', 'CustomerController::createCustomerAccount/$1', ['as' => 'create_customer_account']);
        $routes->post('create-customer-account/(:num)', 'CustomerController::actionCustomerAccount/$1');

        $routes->get('generate', 'CustomerController::generateCustomer', ['as' => 'generate_customer']);
    });

    // order routes
    $routes->group('order', ['namespace' => 'Modules\Acp\Controllers\Store\Order', 'filter' => 'group:superadmin,admin,sale_manager'], function ($routes) {
        $routes->get('/', 'OrderController::index', ['as' => 'order']);
        $routes->post('/', 'OrderController::index');

        $routes->get('add', 'OrderController::addOrder', ['as' => 'add_order']);
        $routes->post('add', 'OrderController::addAction');

        $routes->get('create', 'OrderController::createOrder', ['as' => 'create_order']);

        $routes->get('edit/(:num)', 'OrderController::editOrder/$1', ['as' => 'edit_order']);
        $routes->post('edit/(:num)', 'OrderController::editAction/$1');

        $routes->post('remove', 'OrderController::ajxRemove/$1', ['as' => 'remove_order']);
        $routes->get('order-items/(:num)', 'OrderController::getOrderItem/$1', ['as' => 'items_order']);
        $routes->get('recover/(:num)', 'OrderController::recover/$1', ['as' => 'recover_order']);
        $routes->get('invoice/(:num)', 'OrderController::invoice/$1', ['as' => 'invoice_order']);
        $routes->get('view-deposit/(:num)', 'OrderController::viewDeposit/$1', ['as' => 'view_deposit_order']);
    });
});