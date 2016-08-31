<?php
namespace App\Controller;

use Slim\Router;
use Slim\Views\Twig;

class Controller
{
    protected $twig;
    protected $router;

    public function __construct(Twig $twig, Router $router)
    {
        $this->twig = $twig;
        $this->router = $router;
    }

    public function __call($name, $args)
    {
        if (method_exists($this->twig, $name)) {
            return call_user_func_array([$this->twig, $name], $args);
        }

        if (method_exists($this->router, $name)) {
            return call_user_func_array([$this->router, $name], $args);
        }

        throw new \BadMethodCallException("$name does not exist in base controller");
    }
}
