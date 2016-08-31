<?php
namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Session;

class HomeController extends Controller
{
    public function index(Request $request, Response $response)
    {
        return $this->render($response, "index.twig", [
            "controllerCode" => file_get_contents(__FILE__),
            "viewCode" => file_get_contents(RESOURCES_DIR . "/views/index.twig")
        ]);
    }
}
