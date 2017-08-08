<?php

namespace App\Http;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HttpContentType implements MiddlewareInterface
{
    /**
     * @var string
     */
    private $contentType;

    /**
     * HttpContentType constructor.
     * @param string $contentType
     */
    public function __construct($contentType)
    {
        $this->contentType = $contentType;
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
        return $delegate->process($request)->withHeader('Content-Type', $this->contentType);
    }
}
