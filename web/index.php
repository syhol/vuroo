<?php

/** @var Pimple\Container $container */
$container = require __DIR__ . '/../boot.php';

$response = $container['http-server']
    ->dispatch(Middlewares\Utils\Factory::createServerRequest(
        $_SERVER,
        $_SERVER['REQUEST_METHOD'],
        $_SERVER['REQUEST_URI']
    ));

(new \Zend\Diactoros\Response\SapiEmitter)->emit($response);
