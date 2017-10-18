<?php

namespace App;

use App\Http\HttpContentType;
use App\Http\HttpLogger;
use App\Http\HttpNotFound;
use App\Http\HttpRouteDispatcher;
use App\Http\HttpRouter;
use Aura\Router\RouterContainer;
use Evenement\EventEmitter;
use Evenement\EventEmitterInterface;
use Middlewares\Utils\Dispatcher;
use Middlewares\Utils\Factory\ResponseFactory;
use Monolog\Logger;
use Pimple\Container;
use Pimple\Psr11\Container as Psr11;
use Pimple\ServiceProviderInterface;
use Psr\Container\ContainerInterface;

class AppProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container A container instance
     */
    public function register(Container $container)
    {
        $container['event'] = function () {
            return new EventEmitter();
        };

        $container[ContainerInterface::class] = function () use ($container) {
            return new Psr11($container);
        };

        $container['config'] = function () {
            return require(__DIR__ . '/../etc/app.php');
        };

        $container['log'] = function() {
            return new Logger('app');
        };

        $container['router'] = function() {
            return new HttpRouter(new RouterContainer);
        };

        $container['http-stack'] = function() use ($container) {
            return [
                new HttpContentType('text/plain'),
                new HttpLogger($container['log']),
                new HttpRouteDispatcher($container['router']->getMatcher(), $container[ContainerInterface::class]),
                new HttpNotFound(new ResponseFactory())
            ];
        };

        $container['http-server'] = function() use($container) {
            return new Dispatcher($container['http-stack']);
        };

        $container->extend('event', function(EventEmitterInterface $emitter) use ($container) {
            $emitter->on('boot', function () use ($container) {
                $container['log']->info('Booted', $container['config']);
            });

            return $emitter;
        });

        $container->extend('config', function(array $config) {
            return array_merge($config, [
                'foo' => 'bar'
            ]);
        });
    }
}