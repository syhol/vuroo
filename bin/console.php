#!/usr/bin/env php
<?php

/** @var \DI\Container $container */
$container = require __DIR__ . '/../boot.php';

(new App\Console\ConsoleKernel($container))->run();
