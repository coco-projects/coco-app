<?php

    use Coco\cocoApp\Kernel\Business\ControllerWrapper\WebControllerWrapper;
    use Coco\cocoApp\Kernel\CocoApp;
    use Coco\cocoApp\KernelModule\Http\Controller\Swagger;
    use Slim\App;

    return function(App $app) {
        /**
         * @var CocoApp $cocoApp
         */
        $cocoApp = $app->getContainer()->get('cocoApp');
        $appName = \Coco\cocoApp\KernelModule\Info::getAppName();

        $app->get('/coco_api', WebControllerWrapper::classHandler(Swagger::class, 'page'));
        $app->get('/coco_api_', WebControllerWrapper::classHandler(Swagger::class, 'api'))->setName('coco_api_');

   /*     $app->options('/{routes:.*}', function(Request $request, Response $response) {
            // CORS Pre-Flight OPTIONS Request Handler
            $response = $response
                ->withHeader('Access-Control-Allow-Origin', '*')
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
                ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');

            return $response;
        });

        */
    };