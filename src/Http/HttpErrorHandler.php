<?php

namespace App\Http;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Middlewares\Utils\Traits\HasResponseFactory;
use Throwable;

class HttpErrorHandler implements MiddlewareInterface
{
    use HasResponseFactory;

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
        try {
            return $next->handle($request);
        } catch (Throwable $throwable) {
            $this->logError($throwable, $request);

            $response = $this->createResponse(500);
            $response->getBody()->write($throwable->getMessage() . PHP_EOL);
            return $response;
        }
    }

    /**
     * @param Throwable $throwable
     * @param ServerRequestInterface $request
     * @return string
     */
    public function logError(Throwable $throwable, ServerRequestInterface $request)
    {
        $this->logger->error(
            get_class($throwable) . ': ' . $throwable->getMessage(),
            [
                'request-id' => $request->getAttribute('request-id'),
                'type' => get_class($throwable),
                'message' => $throwable->getMessage(),
                'code' => $throwable->getCode(),
                'file' => $throwable->getFile(),
                'line' => $throwable->getLine(),
            ]
        );
    }
}
