<?php

namespace App\Console;

use Psr\Container\ContainerInterface;
use App\Http\Console\HttpReactServer;

class ConsoleKernel extends \Silly\Application
{
    public function __construct(ContainerInterface $container)
    {
        parent::__construct('App', '1.0.0');
    
        $this->useContainer($container, true, true);

        $this->command('serve [address]', HttpReactServer::class)
            ->descriptions('Run a http server', [
                'address' => 'The interface to bind to',
            ])->defaults([
                'address' => '0.0.0.0:80',
            ]);
    }
}