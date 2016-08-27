<?php
use Interop\Container\ContainerInterface;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use App\Handler\WhoopsErrorHandler;
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
    'errorHandler' => function (ContainerInterface $c) {
        return new WhoopsErrorHandler($c);
    }
];
