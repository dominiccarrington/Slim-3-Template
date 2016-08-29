<?php
session_start();
require_once 'console.php';

/**
 * Create an instance of Slim
 */
$app = new App\App;

/**
 * Setup the container within slim
 */
require_once CONFIG_DIR . '/container.php';

/**
 * Register middleware
 */
require_once APP_DIR . '/middleware.php';

/**
 * Register routes which will be under CSRF protection
 */
$app->group('', function () use ($app) {
    require_once APP_DIR . '/csrf-routes.php';
})->add($app->getContainer()->get('csrf'));

/**
 * Register routes which will not be under CSRF protection
 */
require_once APP_DIR . '/non-csrf-routes.php';
