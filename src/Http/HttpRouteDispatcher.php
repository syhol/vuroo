<?php

namespace App\Http;

use Aura\Router\Matcher;
use DI\Container;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Middlewares\Utils\CallableHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HttpRouteDispatcher implements MiddlewareInterface
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var Matcher
     */
    private $matcher;

    /**
     * Set the RouterContainer instance.
     *
     * @param Matcher $matcher
     * @param Container $container
     */
    public function __construct(Matcher $matcher, Container $container)
    {
        $this->container = $container;
        $this->matcher = $matcher;
    }

    /**
     * Process a server request and return a response.
     *
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        if ($route = $this->matcher->match($request)) {
            foreach ($route->attributes as $name => $value) {
                $request = $request->withAttribute($name, $value);
            }

            return CallableHandler::execute(function () use($route, $request) {
                return $this->container->call($route->handler, [
                    ServerRequestInterface::class => $request
                ] + $route->attributes);
            });
        }

        return $delegate->process($request);
    }
}