<?php

use function DI\autowire;
use function DI\create;
use function DI\get;
use function DI\env;
use function DI\factory;

return [

    Psr\Http\Message\ResponseFactoryInterface::class => create(Middlewares\Utils\Factory\GuzzleFactory::class),
    Psr\Http\Message\ServerRequestFactoryInterface::class => create(Middlewares\Utils\Factory\GuzzleFactory::class),
    Psr\Http\Message\StreamFactoryInterface::class => create(Middlewares\Utils\Factory\GuzzleFactory::class),
    Psr\Http\Message\UriFactoryInterface::class => create(Middlewares\Utils\Factory\GuzzleFactory::class),
    
    'log-handlers' => [
        create(Monolog\Handler\StreamHandler::class)
            ->constructor('php://stderr', Monolog\Logger::INFO)
    ],

    Psr\Log\LoggerInterface::class => autowire(Monolog\Logger::class)
        ->constructor('app', get('log-handlers')),

    Middlewares\Utils\Dispatcher::class => autowire()
        ->constructor(get('http-stack')),

    Aura\Router\Matcher::class => factory([App\Http\HttpRouter::class, 'getMatcher']),

    'env' => env('MY_ENV'),

    'http-stack' => [
        autowire(App\Http\HttpContentType::class)->constructor('text/plain'),
        get(App\Http\HttpLogger::class),
        get(App\Http\HttpRouteDispatcher::class),
        get(App\Http\HttpNotFound::class)
    ]

];
