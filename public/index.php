<?php
/**
 * Root path of project.
 * @var string
 */
define('PROJECT_DIR', dirname(__DIR__));

// Composer autoload
require PROJECT_DIR . "/vendor/autoload.php";

// Initializing Slim Container
$container = (new \Slim\Container(
    [
        'settings' => [
            'displayErrorDetails' => true,
            'determineRouteBeforeAppMiddleware' => true,
            // 'routerCacheFile' => PROJECT_DIR . '/routes.php'
        ]
    ]
))->register(new \gtnsimon\Slim\YAML\RouterProvider(PROJECT_DIR . '/routes.yml'));

// Slim
$app = new \Slim\App($container);
$app->run();