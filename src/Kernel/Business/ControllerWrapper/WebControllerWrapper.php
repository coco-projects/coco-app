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

        public static function classHandler(string $controllerName, string $method): static
        {
            $ins = static::getIns();

            $ins->invokeObject = new $controllerName($ins);
            $ins->invokeMethod = $method;

            return $ins;
        }

        public static function closure(callable $callback): static
        {
            $ins = static::getIns();

            $controller = new WebClosureController($ins);
            $method     = '_' . md5(spl_object_hash($controller));

            $controller::injectMethod($method, $callback);

            $ins->invokeObject = $controller;
            $ins->invokeMethod = $method;

            return $ins;
        }

        public function __invoke(Request $request, Response $response, $args): Response
        {
            $this->request  = $request;
            $this->response = $response;
            $this->args     = $args;

            $this->invokeObject->init();

            return $this->invokeAction();
        }

        protected function invokeAction(): Response
        {
            return call_user_func_array([
                $this->invokeObject,
                $this->invokeMethod,
            ], [$this]);
        }


    }
