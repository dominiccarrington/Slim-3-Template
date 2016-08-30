<?php
define('ROOT_DIR', __DIR__ . '/..');
define('APP_DIR', __DIR__ . '/../app');
define('CONFIG_DIR', __DIR__ . '/../config');
define('RESOURCES_DIR', __DIR__ . '/../resources');

require_once __DIR__ . '/../vendor/autoload.php';

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
