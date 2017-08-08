<?php

use function DI\autowire;
use function DI\get;
use function DI\env;
use function DI\factory;

return [

    Middlewares\Utils\Factory\ResponseFactory::class => autowire(),

    Middlewares\Utils\Dispatcher::class => autowire()
        ->constructor(get('http-stack')),

    'env' => env('MY_ENV'),

    'http-stack' => [

        autowire(App\Http\HttpContentType::class)->constructor('text/plain'),

        autowire(App\Http\HttpLogger::class),

        autowire(App\Http\HttpRouteDispatcher::class)
            ->constructorParameter('matcher', factory([App\Http\HttpRouter::class, 'getMatcher']))
            ->constructorParameter('container', get(DI\Container::class)),

        autowire(App\Http\HttpNotFound::class)
            ->constructorParameter('responseFactory', autowire(Middlewares\Utils\Factory\ResponseFactory::class)),

    ]

];
