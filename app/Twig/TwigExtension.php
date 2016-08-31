<?php
namespace App\Twig;

use App\App;
use Slim\Interfaces\RouterInterface;
use Slim\Http\Uri;

class TwigExtension extends \Twig_Extension
{
    public $router;
    public $uri;

    public function __construct(RouterInterface $router, $uri)
    {
        $this->router = $router;
        $this->uri = $uri;
    }

    public function getName()
    {
        return App::getCurrentAppNamespace();
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction("css", [$this, "css"]),
            new \Twig_SimpleFunction("js", [$this, "js"]),
        ];
    }

    public function css($file)
    {
        if (!file_exists(PUBLIC_DIR . "/css/" . $file)) {
            throw new \Exception(PUBLIC_DIR . "/css/" . $file . " does not exist");
        }
        return $this->uri->getBasePath() . '/css/' . $file;
    }

    public function js($file)
    {
        if (!file_exists(PUBLIC_DIR . "/js/" . $file)) {
            throw new \Exception(PUBLIC_DIR . "/js/" . $file . " does not exist");
        }
        return $this->uri->getBasePath() . '/js/' . $file;
    }
}
