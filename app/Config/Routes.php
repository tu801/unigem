<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get("lang/(:alpha)", '\App\Controllers\Language::setLang/$1', ['as' => 'setLang']);
$routes->get(ADMIN_AREA, '\Modules\Acp\Controllers\Dashboard::index');

// Auth routes
// service('auth')->routes($routes);
// replace default shield route with custom login route 
service('auth')->routes($routes, ['except' => ['login', 'register', 'reset-password']]);
$routes->get('login', '\App\Modules\Auth\Controllers\LoginController::loginView');
$routes->post('login', '\CodeIgniter\Shield\Controllers\LoginController::loginAction');

$routes->get('reset-password', '\App\Modules\Auth\Controllers\ResetPasswordController::resetPasswordView');

$routes->get('reset-password', [App\Modules\Auth\Controllers\ResetPasswordController::class, 'resetPasswordView']);
$routes->post('reset-password', [App\Modules\Auth\Controllers\ResetPasswordController::class, 'resetPasswordAction']);