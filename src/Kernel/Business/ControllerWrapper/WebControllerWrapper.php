<?php

    namespace Coco\cocoApp\Kernel\Business\ControllerWrapper;

    use Coco\cocoApp\Kernel\Business\ControllerAbstract\WebClosureController;
    use Coco\cocoApp\Kernel\Business\ControllerAbstract\WebControllerAbstract;
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;

    class WebControllerWrapper extends ControllerWrapperAbstract
    {
        public ?Request  $request;
        public ?Response $response;
        public array     $args = [];

        private ?WebControllerAbstract $invokeObject;
        private ?string                $invokeMethod;

        public static function classHandler(string $controllerName, string $method): callable
        {
            $ins = static::getIns();

            return function(Request $request, Response $response, $args) use ($ins, $controllerName, $method): Response {

                $ins->request  = $request;
                $ins->response = $response;
                $ins->args     = $args;

                $ins->invokeObject = new $controllerName($ins);
                $ins->invokeMethod = $method;

                $ins->invokeObject->init();

                return call_user_func_array([
                    $ins->invokeObject,
                    $ins->invokeMethod,
                ], [$ins]);
            };
        }

        public static function closure(string $appName, callable $callback): callable
        {
            $ins = static::getIns();

            return function(Request $request, Response $response, $args) use ($ins, $appName, $callback): Response {

                $ins->request  = $request;
                $ins->response = $response;
                $ins->args     = $args;

                $controller = new WebClosureController($appName, $ins);
                $method     = '_' . md5(spl_object_hash($controller));
                $controller::injectMethod($method, $callback);

                $ins->invokeObject = $controller;
                $ins->invokeMethod = $method;

                $ins->invokeObject->init();

                return call_user_func_array([
                    $ins->invokeObject,
                    $ins->invokeMethod,
                ], [$ins]);
            };
        }

    }
