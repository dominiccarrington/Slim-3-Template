<?php
require_once __DIR__ . '/../config/globals.php';
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
