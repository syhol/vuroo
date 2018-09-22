<?php

use function DI\autowire;
use function DI\create;
use function DI\get;
use function DI\env;
use function DI\factory;

use React\EventLoop\LoopInterface;
use React\EventLoop\Factory as LoopFactory;
use Middlewares\Utils\Factory\GuzzleFactory;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
USe Middlewares\Whoops as WhoopsMiddleware;

return [

    LoopInterface::class => factory([LoopFactory::class, 'create']),

    ResponseFactoryInterface::class => create(GuzzleFactory::class),
    ServerRequestFactoryInterface::class => create(GuzzleFactory::class),
    StreamFactoryInterface::class => create(GuzzleFactory::class),
    UriFactoryInterface::class => create(GuzzleFactory::class),
    
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
        get(App\Http\HttpErrorHandler::class),
        get(App\Http\HttpRouteDispatcher::class),
        get(App\Http\HttpNotFound::class)
    ]

];
