<?php

require __DIR__ . '/vendor/autoload.php';

$builder = new DI\ContainerBuilder();

$builder->useAutowiring(true);
$builder->addDefinitions(__DIR__ . '/etc/app.php');

/** @var \DI\Container $container */
$container = $builder->build();
$container->set(DI\Container::class, $container);

return $container;
