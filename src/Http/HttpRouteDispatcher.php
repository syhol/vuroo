<?php

namespace App\Http;

use Aura\Router\Matcher;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Middlewares\Utils\CallableHandler;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HttpRouteDispatcher implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
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
     * @param ContainerInterface $container
     */
    public function __construct(Matcher $matcher, ContainerInterface $container)
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
                $reflect = new \ReflectionFunction($route->handler);
                $args = [];
                foreach ($reflect->getParameters() as $index => $parameter) {
                    $type = (string)$parameter->getType();
                    if (isset($route->attributes[$parameter->getName()])) {
                        $args[$index] = $route->attributes[$parameter->getName()];
                    } elseif($type === ServerRequestInterface::class) {
                        $args[$index] = $request;
                    } elseif(!empty($type)) {
                        $args[$index] = $this->container->get((string)$type);
                    } else {
                        throw new \Exception('Can not resolve route dependency');
                    }
                }
                return call_user_func_array($route->handler, $args);
            });
        }

        return $delegate->process($request);
    }
}