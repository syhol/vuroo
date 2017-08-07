<?php

namespace App\Http;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
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
        $id = substr(bin2hex(random_bytes(5)), 0, 5);
        $this->log($id, $request);
        $response = $delegate->process($request);
        $this->log($id, $request, $response);
        return $response;
    }

    /**
     * @param $id
     * @param ServerRequestInterface $request
     * @param ResponseInterface|null $response
     */
    public function log($id, ServerRequestInterface $request, ResponseInterface $response = null)
    {
        $time = date('Y-m-d H:i:s T');
        $method = $request->getMethod();
        $uri = $request->getUri();
        $type = $response ? 'Response' : 'Request';
        echo "[$time] $type($id) = $method $uri";
        if ($response) {
            echo ' => ' . $response->getStatusCode() . ' ' . $response->getReasonPhrase();
        }
        echo PHP_EOL;
    }
}
