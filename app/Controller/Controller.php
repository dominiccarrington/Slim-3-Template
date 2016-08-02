<?php
namespace App\Controller;

use Slim\Views\Twig;
use Slim\Router;

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
