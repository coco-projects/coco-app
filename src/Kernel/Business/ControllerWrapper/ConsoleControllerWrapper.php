<?php

    namespace Coco\cocoApp\Kernel\Business\ControllerWrapper;

    use Coco\cocoApp\Kernel\Business\ControllerAbstract\ConsoleClosureController;
    use Coco\cocoApp\Kernel\Business\ControllerAbstract\ConsoleControllerAbstract;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;

    class ConsoleControllerWrapper extends ControllerWrapperAbstract
    {
        public ?InputInterface  $input  = null;
        public ?OutputInterface $output = null;

        private ?ConsoleControllerAbstract $invokeObject;
        private ?string                    $invokeMethod;

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

            $controller = new ConsoleClosureController($ins);
            $method     = '_' . md5(spl_object_hash($controller));

            $controller::injectMethod($method, $callback);

            $ins->invokeObject = $controller;
            $ins->invokeMethod = $method;

            return $ins;
        }

        public function __invoke(InputInterface $input, OutputInterface $output)
        {
            $this->input  = $input;
            $this->output = $output;

            $this->invokeObject->init();

            return $this->invokeAction();
        }

        protected function invokeAction()
        {
            return call_user_func_array([
                $this->invokeObject,
                $this->invokeMethod,
            ], [$this]);
        }

    }
