<?php
session_start();

define('APP_DIR', __DIR__ . '/../app');
define('CONFIG_DIR', __DIR__ . '/../config');
define('RESOURCES_DIR', __DIR__ . '/../resources');
define('ROUTES_DIR', __DIR__ . '/../routes');

require_once __DIR__ . '/../vendor/autoload.php';

$env = new Dotenv\Dotenv(__DIR__ . "/..");
$env->load();

/**
 * Register class aliases
 */
foreach (require CONFIG_DIR . "/shortcuts.php" as $short => $full) {
    class_alias($full, $short);
}

/**
 * Create an instance of Slim
 */
$app = new App\App;

/**
 * Create connection to database
 */
require_once APP_DIR . '/database.php';

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
