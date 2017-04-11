<?php
namespace App\Middleware;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\MessageBag;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Session;
use Slim\Views\Twig;

class TwigValuesMiddleware extends Middleware
{
    protected function runMiddleware(Request $request, Response $response, callable $next)
    {
        $container = $this->getContainer();
        $twig = $container->get(Twig::class);
        $twigEnvironment = $twig->getEnvironment();

        $this->values($request, $response, $container, $twigEnvironment);
        $this->validationErrors($request, $response, $container, $twigEnvironment);
        $this->csrfValues($request, $response, $container, $twigEnvironment);
        $this->paginationSetup($request, $response, $container, $twigEnvironment);

        $response = $next($request, $response);
        return $response;
    }

    private function values(Request $request, Response $response, $container, $twigEnvironment)
    {
        $twigEnvironment->addGlobal('post', Session::get('postValues'));
        $twigEnvironment->addGlobal('get', $request->getQueryParams());
        Session::set('postValues', $request->getParsedBody());
    }

    private function validationErrors(Request $request, Response $response, $container, $twigEnvironment)
    {
        $mb = new MessageBag(Session::get('validation_errors') ?: []);
        $twigEnvironment->addGlobal('errors', $mb);
        Session::delete('validation_errors');
    }

    private function csrfValues(Request $request, Response $response, $container, $twigEnvironment)
    {
        $csrf = $container->get('csrf');
        $nameKey = $csrf->getTokenNameKey();
        $valueKey = $csrf->getTokenValueKey();

        $csrf->generateToken();

        $name = $csrf->getTokenName();
        $value = $csrf->getTokenValue();

        $twigEnvironment->addGlobal("csrf:nameKey", $nameKey);
        $twigEnvironment->addGlobal("csrf:valueKey", $valueKey);
        $twigEnvironment->addGlobal("csrf:name", $name);
        $twigEnvironment->addGlobal("csrf:value", $value);
        $twigEnvironment->addGlobal("csrf:inputs", "
            <input type='hidden' name='" . $nameKey . "' value='" . $name . "'>
            <input type='hidden' name='" . $valueKey . "' value='" . $value . "'>
        ");
    }

    private function paginationSetup(Request $request, Response $response, $container, $twigEnvironment)
    {
        Paginator::currentPageResolver(function ($pageName) use ($request) {
            $params = $request->getQueryParams();
            return isset($params[$pageName]) ? (int) $params[$pageName] : 1;
        });

        Paginator::currentPathResolver(function () use ($request) {
            return preg_replace("/(\?|&)page=\d*/", "", $request->getUri());
        });
    }
}
