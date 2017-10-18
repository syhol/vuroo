<?php

namespace App\Base;

use Aura\Router\RouterContainer;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class BaseProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     */
    public function register(Container $container)
    {
        $container->extend(RouterContainer::class, function (RouterContainer $router) {
            (new HttpRouteMapper)->bindRoutes($router->getMap());
            return $router;
        });
    }
}