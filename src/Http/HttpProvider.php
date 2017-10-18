<?php

namespace App\Http;

use Aura\Router\Map;
use Aura\Router\Matcher;
use Aura\Router\RouterContainer;
use Middlewares\Utils\Dispatcher;
use Middlewares\Utils\Factory\ResponseFactory;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Psr\Container\ContainerInterface;

class HttpProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     */
    public function register(Container $container)
    {
        $container[RouterContainer::class] = function() {
            return new RouterContainer;
        };

        $container[Matcher::class] = function() use($container) {
            return $container[RouterContainer::class]->getMatcher();
        };

        $container['http-stack'] = function() use ($container) {
            return [
                new HttpContentType('text/plain'),
                new HttpLogger($container['log']),
                new HttpRouteDispatcher($container[Matcher::class], $container[ContainerInterface::class]),
                new HttpNotFound(new ResponseFactory())
            ];
        };

        $container['http-server'] = function() use($container) {
            return new Dispatcher($container['http-stack']);
        };
    }
}