<?php
namespace App\Middleware;

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

        // OLD POST VALUES
        $twigEnvironment->addGlobal('values', Session::get('postValues'));
        Session::set('postValues', $request->getParsedBody());

        // VALIDATION ERRORS
        $mb = new MessageBag(Session::get('validation_errors') ?: []);
        $twigEnvironment->addGlobal('errors', $mb);
        Session::delete('validation_errors');

        // CSRF VALUES
        $csrf = $container->get('csrf');
        $nameKey = $csrf->getTokenNameKey();
        $valueKey = $csrf->getTokenValueKey();

        $csrf->generateToken();

        $name = $csrf->getTokenName();
        $value = $csrf->getTokenValue();

        $twigEnvironment->addGlobal("csrf_nameKey", $nameKey);
        $twigEnvironment->addGlobal("csrf_valueKey", $valueKey);
        $twigEnvironment->addGlobal("csrf_name", $name);
        $twigEnvironment->addGlobal("csrf_value", $value);
        $twigEnvironment->addGlobal("csrf_inputs", "
            <input type='hidden' name='" . $nameKey . "' value='" . $name . "'>
            <input type='hidden' name='" . $valueKey . "' value='" . $value . "'>
        ");

        // Pagination Setup
        Paginator::currentPageResolver(function ($pageName) use ($request) {
            $params = $request->getQueryParams();
            return isset($params[$pageName]) ? (int) $params[$pageName] : 1;
        });

        Paginator::currentPathResolver(function () use ($request) {
            return preg_replace("/(\?|&)page=\d*/", "", $request->getUri());
        });

        $response = $next($request, $response);
        return $response;
    }
}
