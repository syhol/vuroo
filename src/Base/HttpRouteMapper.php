<?php

namespace App\Base;

use Aura\Router\Map;
use Psr\Container\ContainerInterface;

class HttpRouteMapper
{
    /**
     * @param Map $map
     */
    public function bindRoutes(Map $map)
    {
        $map->get('home', '/', function() {
            return 'Welcome Home' . PHP_EOL;
        });

        $map->get('env', '/env', function(ContainerInterface $container) {
            return 'Env: ' . $container->get('config')['foo'] . PHP_EOL;
        });

        $map->get('greet', '/greet/{name}', function($name) {
            return 'Hello ' . $name . PHP_EOL;
        });
    }
}