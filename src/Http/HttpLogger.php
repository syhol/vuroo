<?php

namespace App\Http;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

class HttpLogger implements MiddlewareInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * HttpLogger constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
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
        $id = substr(bin2hex(random_bytes(5)), 0, 5);
        $this->logger->info($this->buildLog($id, $request));
        $response = $next->handle($request);
        $this->logger->info($this->buildLog($id, $request, $response), ['foo' => 'bar']);
        return $response;
    }

    /**
     * @param $id
     * @param ServerRequestInterface $request
     * @param ResponseInterface|null $response
     * @return string
     */
    public function buildLog($id, ServerRequestInterface $request, ResponseInterface $response = null)
    {
        $method = $request->getMethod();
        $uri = $request->getUri()->getPath();
        $type = $response ? 'Response' : 'Request';
        $message = "$type($id) = $method $uri";
        if ($response) {
            $message .= ' => ' . $response->getStatusCode();
        }
        return $message . PHP_EOL;
    }
}
