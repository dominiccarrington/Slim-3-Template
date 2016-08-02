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
}
