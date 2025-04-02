<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Auth routes
service('auth')->routes($routes);

$routes->get('reset-password', [App\Modules\Auth\Controllers\ResetPasswordController::class, 'resetPasswordView']);
$routes->post('reset-password', [App\Modules\Auth\Controllers\ResetPasswordController::class, 'resetPasswordAction']);