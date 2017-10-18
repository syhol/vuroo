#!/usr/bin/env php
<?php

/** @var \Pimple\Container $container */
$container = require __DIR__ . '/../boot.php';

//$loop = React\EventLoop\Factory::create();
//
//$bind = '0.0.0.0:80';
//$dispatcher = $container[Middlewares\Utils\Dispatcher::class];
//$server = new React\Http\Server([$dispatcher, 'dispatch']);
//$socket = new React\Socket\Server($bind, $loop);
//$server->listen($socket);
//
//echo "Server running at http://$bind\n";
//
//$loop->run();
