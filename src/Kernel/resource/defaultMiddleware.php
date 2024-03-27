<?php

    use Coco\cocoApp\Kernel\Middleware\AroundRun;
    use Slim\App;

    return function(App $app) {

        $app->add(new AroundRun());

    };
