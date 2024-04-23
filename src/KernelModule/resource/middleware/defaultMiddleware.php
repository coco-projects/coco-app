<?php

    use Coco\cocoApp\KernelModule\Http\Middleware\AroundRun;
    use Middlewares\TrailingSlash;
    use Slim\App;

    return function(App $app) {

        $app->addBodyParsingMiddleware();

        $app->add(new TrailingSlash(false));

//        $app->add(new \Slim\Middleware\ContentLengthMiddleware());

        $app->add(new AroundRun());
    };
