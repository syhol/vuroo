<?php

namespace App;

use Evenement\EventEmitter;
use Evenement\EventEmitterInterface;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class AppProvider implements ServiceProviderInterface
{

    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $container A container instance
     */
    public function register(Container $container)
    {
        $container['event'] = function () {
            return new EventEmitter();
        };

        $container->extend('event', function(EventEmitterInterface $emitter) {

            $emitter->on('boot', function () {
                echo "Booted\n";
            });

            return $emitter;
        });
    }
}