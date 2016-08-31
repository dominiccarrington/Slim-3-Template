<?php
define('ROOT_DIR', __DIR__ . '/..');
define('APP_DIR', __DIR__ . '/../app');
define('CONFIG_DIR', __DIR__ . '/../config');
define('RESOURCES_DIR', __DIR__ . '/../resources');
define('STUB_DIR', __DIR__ . '/../stubs');
define('PUBLIC_DIR', __DIR__ . '/../public');
define("CONSOLE_MODE", php_sapi_name() == 'cli');

require_once __DIR__ . '/../vendor/autoload.php';
require_once APP_DIR . '/functions.php';

$env = new Dotenv\Dotenv(__DIR__ . "/..");
$env->load();

/**
 * Create connection to database
 */
require_once APP_DIR . '/database.php';

/**
 * Register class aliases
 */
foreach (require CONFIG_DIR . "/shortcuts.php" as $short => $full) {
    class_alias($full, $short);
}
