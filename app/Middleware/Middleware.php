<?php
namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

abstract class Middleware
{
    protected $app;
    protected $router; 

    public function __construct(\Slim\App $app)
    {
        $this->app = $app;
        $this->router = $this->app->getContainer()->get('router');
    }

    public function __invoke(Request $request, Response $response, callable $next)
    {
        return $this->runMiddleware($request, $response, $next);
    }

    protected abstract function runMiddleware(Request $request, Response $response, callable $next);

    public function __call($name, $args)
    {
        return call_user_func_array([$this->app, $name], $args);
    }
}