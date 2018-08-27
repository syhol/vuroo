<?php

namespace App\Http;

use Aura\Router\Matcher;
use DI\Container;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
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
     * @param RequestHandlerInterface $next
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $next): ResponseInterface
    {
        if ($route = $this->matcher->match($request)) {
            foreach ($route->attributes as $name => $value) {
                $request = $request->withAttribute($name, $value);
            }

            return (new CallableHandler(function () use($route, $request) {
                return $this->container->call(
                    $route->handler,
                    [ServerRequestInterface::class => $request] + $route->attributes
                );
            }))->handle($request);
        }

        return $next->handle($request);
    }
}