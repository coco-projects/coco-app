<?php

    use Coco\cocoApp\CocoApp;
    use Coco\cocoApp\Kernel\Server\WebSiteServer;

    require '../vendor/autoload.php';

    $app    = CocoApp::init(__DIR__ . '/public');
    $server = new WebSiteServer($app);
    $server->listen();
