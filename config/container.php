<?php
use App\Handler\WhoopsErrorHandler;
use Interop\Container\ContainerInterface;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run as WhoopsRun;
use function DI\get;

return [
    'router' => get(Slim\Router::class),
    'csrf' => function (ContainerInterface $c) {
        return new \Slim\Csrf\Guard;
    },
    'flash' => function (ContainerInterface $c) {
        return new \Slim\Flash\Messages;
    },
    Twig::class => function (ContainerInterface $c) {
        $twig = new Twig(RESOURCES_DIR . '/views', [
            'cache' => __DIR__ . "/../storage/views/"
        ]);
        $TwigExtension = new TwigExtension($c->get('router'), $c->get('request')->getUri());
        define('BASE_PATH', $TwigExtension->baseUrl());
        $twig->addExtension($TwigExtension);

        return $twig;
    },
    'whoops' => function (ContainerInterface $c) {
        $whoops = new WhoopsRun();
        $environment = $c->get('environment');

        $prettyPageHandler = new PrettyPageHandler();
        $prettyPageHandler->setEditor(getenv('EDITOR') ?: 'sublime');

        $prettyPageHandler->addDataTable('Slim Application', [
            'Application Class' => get_class($this),
            'Script Name'       => $environment->get('SCRIPT_NAME'),
            'Request URI'       => $environment->get('PATH_INFO') ?: '<none>',
        ]);

        $whoops->pushHandler($prettyPageHandler);
        if (\Whoops\Util\Misc::isAjaxRequest()) {
            $whoops->pushHandler(new JsonResponseHandler);
        }

        return $whoops;
    },
    'errorHandler' => function (ContainerInterface $c) {
        return new WhoopsErrorHandler($c);
    }
];
