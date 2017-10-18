<?php

require __DIR__ . '/vendor/autoload.php';

$container = new Pimple\Container();

$container->register(new App\AppProvider);

$container['event']->emit('boot');

return $container;
