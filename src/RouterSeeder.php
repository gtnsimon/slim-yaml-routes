<?php
namespace gtnsimon\Slim\YAML;

use Psr\Container\ContainerInterface;
use Slim\Interfaces\RouterInterface;
use Slim\RouteGroup;

/**
 * RouterSeeder
 * ============
 *
 * YAML routes definitions for Slim Framework 3.
 *
 * @package gtnsimon\Slim\YAML
 */
class RouterSeeder
{
    /**
     * Default options.
     * @var array
     */
    private $options = [
        'callables' => [
            'namespaces' => ""
        ],
        'middlewares' => [
            'namespaces' => ""
        ]
    ];

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param RouterInterface $router
     * @param ContainerInterface $container
     * @param array $routes
     * @param array $options
     *
     * @throws \Exception
     */
    public function __construct(
        RouterInterface $router,
        ContainerInterface $container,
        array $routes,
        array $options
    )
    {
        $this->router = $router;
        $this->container = $container;
        $this->options = array_merge($this->options, $options);

        $this->map($routes);
    }

    /**
     * @return RouterInterface
     */
    public function getRouter(): RouterInterface
    {
        return $this->router;
    }

    /**
     * @param array|null $routes
     * @throws \Exception
     */
    public function map(?array $routes): void
    {
        foreach ($routes as $routeName => $route) {
            if ($routeName === 'middlewares') { // middlewares of application (for all routes)
                throw new \Exception("Top middlewares must be added to the Slim\\App instance using add() method");
            } elseif (isset($route['routes'])) { // definition of a group of routes
                $group = &$route;
                $groupName = &$routeName;
                $pattern = $group['pattern'] ?? "/";
                $groupRoutes = $group['routes'] ?? [];
                $middlewares = $group['middlewares'] ?? [];

                // no pattern
                if ($pattern === null) {
                    throw new \LogicException("A group must have a 'pattern' key");
                } elseif ($pattern === "/") {
                    $pattern = "";
                }

                /**
                 * @var RouteGroup
                 */
                $Group = ($this->router->pushGroup(
                    $pattern,
                    function () { /* ... */ }
                ))->setContainer($this->container);

                // add group middlewares
                foreach ($middlewares as $middleware) {
                    $Group->add($this->preprendNamespace($middleware, $this->options['middlewares']['namespaces']));
                }

                // map routes inside the group
                if (!empty($groupRoutes)) {
                    foreach ($groupRoutes as $k => $groupRoute) {
                        $parents = $groupRoutes[$k]['parents'] ?? [];
                        $parents[] = $groupName;
                        $groupRoutes[$k]['parents'] = $parents;
                    }

                    $this->map($groupRoutes);
                }

                // group mapping is finished so we remove it
                $this->router->popGroup();
            } else { // definition of a route
                $methods = $route['methods'] ?? ['GET'];
                $pattern = $route['pattern'] ?? null;
                $callable = $route['callable'] ?? null;
                $middlewares = $route['middlewares'] ?? [];

                // methods empty in definitions
                if (empty($methods)) {
                    $methods = ['GET'];
                }

                // no pattern
                if ($pattern === null) {
                    $pattern = "[/]";
                }

                // no callable
                if ($callable === null) {
                    throw new \LogicException("A route must have a 'callable' key");
                } else {
                    $callable = str_replace('@', ':', $callable);
                }

                // creating the new route
                $Route = $this->router->map(
                    $methods,
                    $pattern,
                    $this->preprendNamespace($callable, $this->options['callables']['namespaces'])
                )
                    ->setName($routeName)
                    ->setArguments($route['arguments'] ?? []);

                // add route middlewares
                foreach ($middlewares as $middleware) {
                    $Route->add($this->preprendNamespace($middleware, $this->options['middlewares']['namespaces']));
                }
            }
        }
    }

    /**
     * @param string $string
     * @param string $namespace
     * @return string
     */
    protected function preprendNamespace(string $string, string $namespace): string
    {
        if (substr($string, 0, 1) !== "\\") {
            $string = $namespace . "\\" . $string;
        }

        return trim($string, "\\");
    }
}