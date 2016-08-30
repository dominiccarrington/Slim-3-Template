<?php
namespace App\Handler;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run as WhoopsRun;

class WhoopsErrorHandler
{
    private $container;

    public function __construct(ContainerInterface $c)
    {
        $this->container = $c;
    }

    public function __invoke(Request $request, Response $repsonse, $e)
    {
        $whoops = $this->container->get('whoops');
        $prettyPageHandler = $whoops->getHandlers()[0];
        $prettyPageHandler->addDataTable('Slim Application (Request)', [
            'Accept Charset'  => $request->getHeader('ACCEPT_CHARSET') ?: '<none>',
            'Content Charset' => $request->getContentCharset() ?: '<none>',
            'Path'            => $request->getUri()->getPath(),
            'Query String'    => $request->getUri()->getQuery() ?: '<none>',
            'HTTP Method'     => $request->getMethod(),
            'Base URL'        => (string) $request->getUri(),
            'Scheme'          => $request->getUri()->getScheme(),
            'Port'            => $request->getUri()->getPort(),
            'Host'            => $request->getUri()->getHost(),
        ]);

        $handler = WhoopsRun::EXCEPTION_HANDLER;

        ob_start();
        $whoops->$handler($e);
        $content = ob_get_clean();

        $code = $exception instanceof HttpException ? $exception->getStatusCode() : 500;
        return $response
            ->withStatus($code)
            ->withHeader('Content-type', 'text/html')
            ->write($content);
    }
}
