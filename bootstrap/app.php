<?php
use Illuminate\Support\MessageBag;
use Slim\Views\Twig;
use Symfony\Component\Finder\Finder;

session_start();
require_once 'console.php';

/**
 * Create an instance of Slim
 */
$app = new App\App;

$app->getContainer()->get('whoops')->register();

/**
 * Register middleware
 */
require_once CONFIG_DIR . '/middleware.php';

/**
 * Register routes
 */
 $files = Finder::create()
        ->in(ROUTES_DIR)
        ->name("*.php");

foreach ($files as $file) {
    require $file;
}
