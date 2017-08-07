#!/usr/bin/env php
<?php

/** @var \DI\Container $container */
$container = require __DIR__ . '/../boot.php';

$loop = React\EventLoop\Factory::create();

$dispatcher = $container->get(Middlewares\Utils\Dispatcher::class);
$server = new React\Http\Server([$dispatcher, 'dispatch']);
$socket = new React\Socket\Server('0.0.0.0:80', $loop);
$server->listen($socket);

echo "Server running at http://0.0.0.0:80\n";

$loop->run();
