<?php

namespace App\Http;

use Aura\Router\Map;
use Aura\Router\Matcher;
use Aura\Router\RouterContainer;
use DI\Container;

class HttpRouter
{
    /**
     * Set the RouterContainer instance.
     *
     * @param RouterContainer $router
     */
    public function __construct(RouterContainer $router)
    {
        $this->router = $router;
        $this->bindRoutes($router->getMap());
    }

    /**
     * @param Map $map
     */
    public function bindRoutes(Map $map)
    {
        $map->get('home', '/', function() {
            return 'Welcome Home In App' . PHP_EOL;
        });

        $map->get('env', '/env', function(Container $container) {
            return 'Env: ' . $container->get('env') . PHP_EOL;
        });

        $map->get('greet', '/greet/{name}', function($name) {
            return 'Hello ' . $name . PHP_EOL;
        });
    }

    /**
     * @return Matcher
     */
    public function getMatcher()
    {
        return $this->router->getMatcher();
    }
}