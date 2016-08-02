<?php
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
