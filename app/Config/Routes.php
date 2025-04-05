<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get("lang/(:alpha)", '\App\Controllers\Language::setLang/$1', ['as' => 'setLang']);
$routes->get(ADMIN_AREA, '\Modules\Acp\Controllers\Dashboard::index');

// Auth routes
service('auth')->routes($routes);

$routes->get('reset-password', [App\Modules\Auth\Controllers\ResetPasswordController::class, 'resetPasswordView']);
$routes->post('reset-password', [App\Modules\Auth\Controllers\ResetPasswordController::class, 'resetPasswordAction']);