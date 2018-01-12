<?php
namespace App\Controller;

use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class IndexController
{
    public function index(Request $request, Response $response): ResponseInterface
    {
        $route = $request->getAttribute('route');

        return $response->write($route->getName());
    }

    public function hello(Request $request, Response $response): ResponseInterface
    {
        $route = $request->getAttribute('route');

        return $response->write(implode(', ', $route->getArguments()));
    }
}