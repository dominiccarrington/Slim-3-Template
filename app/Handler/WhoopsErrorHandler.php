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
        $whoops = new WhoopsRun();

        $environment = $this->container->get('environment');

        $prettyPageHandler = new PrettyPageHandler();
        $prettyPageHandler->setEditor(getenv('EDITOR') ?: 'sublime');

        $prettyPageHandler->addDataTable('Slim Application', [
            'Application Class' => get_class($this),
            'Script Name'       => $environment->get('SCRIPT_NAME'),
            'Request URI'       => $environment->get('PATH_INFO') ?: '<none>',
        ]);
        $prettyPageHandler->addDataTable('Slim Application (Request)', array(
            'Accept Charset'  => $request->getHeader('ACCEPT_CHARSET') ?: '<none>',
            'Content Charset' => $request->getContentCharset() ?: '<none>',
            'Path'            => $request->getUri()->getPath(),
            'Query String'    => $request->getUri()->getQuery() ?: '<none>',
            'HTTP Method'     => $request->getMethod(),
            'Base URL'        => (string) $request->getUri(),
            'Scheme'          => $request->getUri()->getScheme(),
            'Port'            => $request->getUri()->getPort(),
            'Host'            => $request->getUri()->getHost(),
        ));

        $whoops->pushHandler($prettyPageHandler);
        if (\Whoops\Util\Misc::isAjaxRequest()) {
            $whoops->pushHandler(new JsonResponseHandler);
        }

        $whoops->register();

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
