<?php

require __DIR__ . '/vendor/autoload.php';

$container = new Pimple\Container();

$container->register(new App\AppProvider);
$container->register(new App\Http\HttpProvider);
$container->register(new App\Base\BaseProvider);

$container['event']->emit('boot');

return $container;
