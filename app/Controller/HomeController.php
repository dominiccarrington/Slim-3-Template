<?php
namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Session;

class HomeController extends Controller
{
    public function index(Request $request, Response $response)
    {
        return $this->render($response, "index.twig");
    }
}
