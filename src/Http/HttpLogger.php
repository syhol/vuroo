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
        $request = $request
            ->withAttribute('request-id', substr(bin2hex(random_bytes(7)), 0, 7))
            ->withAttribute('start-time', microtime(true));

        $response = $next->handle($request);
        $this->logEvent($request, $response);

        return $response;
    }

    /**
     * @param $id
     * @param ServerRequestInterface $request
     * @param ResponseInterface|null $response
     * @return string
     */
    public function logEvent(ServerRequestInterface $request, ResponseInterface $response = null)
    {
        $uri = $request->getUri();
        parse_str($uri->getQuery(), $query);
        $startTime = $request->getAttribute('start-time');

        $this->logger->info(
            'HTTP Req/Res',
            [
                'request-id' => $request->getAttribute('request-id'),
                'duration' => microtime(true) - $startTime,
                'http' => [
                    'status-code' => $response->getStatusCode(),
                    'method' => $request->getMethod(),
                    'scheme' => $uri->getScheme(),
                    'host' => $uri->getHost(),
                    'port' => $uri->getPort(),
                    'path' => $uri->getPath(),
                    'query' => $query,
                ]
            ]
        );
    }
}
