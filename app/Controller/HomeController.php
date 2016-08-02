<?php
namespace App\Controller;

use Session;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class HomeController extends Controller
{
    public function index(Request $request, Response $response)
    {
        return $this->twig->render($response, "index.twig");
    }
}
