<?php

namespace App\Http;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Middlewares\Utils\Factory\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HttpNotFound implements MiddlewareInterface
{
    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * HttpNotFound constructor.
     * @param ResponseFactory $responseFactory
     */
    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $response = $this->responseFactory->createResponse(404);

        $response->getBody()->write('Could not find route: ' . $request->getUri());

        return $response;
    }
}