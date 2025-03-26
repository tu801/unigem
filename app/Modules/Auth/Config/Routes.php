<?php

namespace Modules\Auth\Config;
/**
 * Modules:Auth routes file.
 */

use Modules\Router\RouteCollection;
use Modules\Auth\Config\Auth as AuthConfig;

/** @var RouteCollection $routes */
$routes->group('auth', ['namespace' => 'Modules\Auth\Controllers'], static function($routes)
{
    // Load the reserved routes from Auth.php
    $config         = config(AuthConfig::class);
    $reservedRoutes = $config->reservedRoutes;

    // Login/out
    $routes->get('login', 'AuthController::login', ['as' => 'login']);
    $routes->post('login', 'AuthController::attemptLogin');
    $routes->get('logout', 'AuthController::logout');

    // Registration
    $routes->get('register', 'AuthController::register', ['as' => 'register']);
    $routes->post('register', 'AuthController::attemptRegister');

    // Activation
    $routes->get('activate-account', 'AuthController::activateAccount', ['as' => 'activate-account']);
    $routes->get('resend-activate-account', 'AuthController::resendActivateAccount', ['as' => 'resend-activate-account']);

    // Forgot/Resets
    $routes->get('forgot', 'AuthController::forgotPassword', ['as' => 'forgot']);
    $routes->post('forgot', 'AuthController::attemptForgot');
    $routes->get('reset-password', 'AuthController::resetPassword', ['as' => 'reset-password']);
    $routes->post('reset-password', 'AuthController::attemptReset');

    // Forece Reset password
    $routes->get('change-password', 'AuthController::changePassword', ['as' => 'change-password']);
    $routes->post('change-password', 'AuthController::attemptChangePass');

});