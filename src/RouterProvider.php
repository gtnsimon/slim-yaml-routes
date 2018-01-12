<?php
namespace gtnsimon\Slim\YAML;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Psr\Container\ContainerInterface;
use Slim\Interfaces\RouterInterface;
use Symfony\Component\Yaml\Yaml;

class RouterProvider implements ServiceProviderInterface
{
    /**
     * @var string
     */
    const ROUTER_KEY = "router";

    /**
     * @var string
     */
    private $routesFile;

    /**
     * @param null|string $routesFile
     */
    public function __construct(?string $routesFile = null)
    {
        $this->routesFile = $routesFile ?? 'routes.yml';
    }

    /**
     * Extends Slim Router to provide routes using YAML files.
     *
     * @param Container $pimple A container instance
     */
    public function register(Container $pimple)
    {
        $pimple->extend(static::ROUTER_KEY, function (RouterInterface $router, ContainerInterface $container) {
            // parse YAML file as array
            $routes = Yaml::parseFile($this->routesFile);

            // start seeding the router
            $routerSeeder = (new RouterSeeder(
                $router,
                $container,
                $routes['routes'] ?? [],
                $routes['options'] ?? []
            ));

            return $router;
        });
    }
}