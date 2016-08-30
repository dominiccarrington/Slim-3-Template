<?php
use Slim\Views\Twig;
use Illuminate\Support\MessageBag;

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
require_once CONFIG_DIR . '/routes.php';

