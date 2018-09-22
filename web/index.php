<?php

/** @var DI\Container $container */
$container = require __DIR__ . '/../boot.php';

$request = Zend\Diactoros\ServerRequestFactory::fromGlobals();

$response = $container->get(Middlewares\Utils\Dispatcher::class)
    ->dispatch($request);

$response = $container->get(Zend\Diactoros\Response\SapiStreamEmitter::class)
    ->emit($response);
