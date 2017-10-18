<?php

namespace App;

use Evenement\EventEmitter;
use Evenement\EventEmitterInterface;
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
        $container[ContainerInterface::class] = function () use ($container) {
            return new Psr11($container);
        };

        $container['event'] = function () {
            return new EventEmitter();
        };

        $container['config'] = function () {
            return require(__DIR__ . '/../etc/app.php');
        };

        $container['log'] = function() {
            return new Logger('app');
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