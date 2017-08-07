<?php

namespace App\Http;

use Aura\Router\Matcher;
use DI\Container;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Middlewares\Utils\CallableHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HttpLogger implements MiddlewareInterface
{

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
        $id = random_bytes(5);
        $time = date(DATE_RFC3339);
        $method = $request->getMethod();
        $uri = $request->getUri();
        echo "$time Request : $id - $method $uri";
        $response = $delegate->process($request);
        $code = $response->getStatusCode();
        $phrase = $response->getReasonPhrase();
        echo "$time Response: $id - $method $uri => $code $phrase";
        return $response;
    }
}