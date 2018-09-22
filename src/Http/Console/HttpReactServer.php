<?php

namespace App\Http\Console;

use Middlewares\Utils\Dispatcher;
use React\EventLoop\LoopInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class HttpReactServer
{
    /**
     * @var LoopInterface
     */
    private $loop;

    /**
     * @var Dispatcher
     */
    private $dispatcher;

    public function __construct(LoopInterface $loop, Dispatcher $dispatcher)
    {
        $this->loop = $loop;
        $this->dispatcher = $dispatcher;
    }

    public function __invoke($address, SymfonyStyle $io)
    {
        $server = new \React\Http\Server([$this->dispatcher, 'dispatch']);
        $socket = new \React\Socket\Server($address, $this->loop);
        $server->listen($socket);
        $io->writeln('Server listening on ' . $socket->getAddress());
        $this->loop->run();
    }
}
