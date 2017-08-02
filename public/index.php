<?php

/** @var DI\Container $container */
$container = require __DIR__ . '/../boot.php';

$response = $container->get(Middlewares\Utils\Dispatcher::class)
    ->dispatch(Middlewares\Utils\Factory::createServerRequest(
        $_SERVER,
        $_SERVER['REQUEST_METHOD'],
        $_SERVER['REQUEST_URI']
    ));

Http\Response\send($response);
