<?php

use function DI\object;
use function DI\get;
use function DI\env;
use function DI\factory;

return [
    DI\Container::class => object(),
    App\Http\HttpNotFound::class => object(),
    App\Http\HttpRouter::class => object(),
    App\Http\HttpLogger::class => object(),

    Middlewares\Utils\Dispatcher::class => object()
        ->constructor(get('http-stack')),

    'env' => env('MY_ENV'),

    'http-stack' => [

        get(App\Http\HttpLogger::class),

        object(App\Http\HttpRouteDispatcher::class)
            ->constructorParameter('matcher', factory([App\Http\HttpRouter::class, 'getMatcher']))
            ->constructorParameter('container', get(DI\Container::class)),

        get(App\Http\HttpNotFound::class)

    ]

];
