# slim-yaml-routes (W.I.P)

A package for Slim Framework 3 allowing you to define \Slim\App routes using YAML file.

```php
<?php
// require 'vendor/autoload.php'

// instantiate Slim Container with \Slim\App settings:
// https://www.slimframework.com/docs/objects/application.html
$container = new \Slim\Container(/* ['settings' => [...] */);

// add slim-yaml-routes to extends the Router
$container->register(new \gtnsimon\Slim\YAML\RouterProvider(__DIR__ . '/routes.yml'));

$app = new \Slim\App($container);
$app->run();
```

## TODO

- Documentation on how to define routes in the .yml file.
