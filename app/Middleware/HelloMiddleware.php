<?php
namespace App\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class HelloMiddleware
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param callable $next
     *
     * @return ResponseInterface
     */
    public function __invoke(Request $request, Response $response, callable $next): ResponseInterface
    {
        $response = $response->write("hello ");



        return $next($request, $response);
    }
}