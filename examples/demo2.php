<?php

    use Coco\cocoApp\CocoApp;
    use Coco\cocoApp\Kernel\Server\ConsoleServer;

    require '../vendor/autoload.php';

    $app    = CocoApp::init(__DIR__ . '/public');
    $server = new ConsoleServer($app);
    $server->listen();