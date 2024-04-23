<?php

    namespace Coco\cocoApp\Kernel\Business\ControllerWrapper;

    use Coco\cocoApp\Kernel\Business\ConsleCommand;
    use Coco\cocoApp\Kernel\Business\ControllerAbstract\ConsoleClosureController;
    use Coco\cocoApp\Kernel\Business\ControllerAbstract\ConsoleControllerAbstract;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;

    class ConsoleControllerWrapper extends ControllerWrapperAbstract
    {
        public ?InputInterface  $input  = null;
        public ?OutputInterface $output = null;
        public array            $params = [];

        private ?ConsoleControllerAbstract $invokeObject;
        private ?string                    $invokeMethod;

        public static function classHandler(string $controllerName, string $method): callable
        {
            $ins = static::getIns();

            return function(InputInterface $input, OutputInterface $output) use ($ins, $controllerName, $method): int {
                $ins->input  = $input;
                $ins->output = $output;
                $ins->initParams($input);

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

            return function(InputInterface $input, OutputInterface $output) use ($ins, $appName, $callback): int {

                $ins->input  = $input;
                $ins->output = $output;
                $ins->initParams($input);

                $controller = new ConsoleClosureController($appName, $ins);
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


        protected function initParams(InputInterface $input): static
        {
            $paramsString = $input->getOption(ConsleCommand::PARAMS);
            $paramsType   = $input->getOption(ConsleCommand::PARAMS_TYPE);

            if ($paramsType == ConsleCommand::TYPE_JSON)
            {
                $this->params = json_decode($paramsString, 1);
            }
            elseif ($paramsType == ConsleCommand::TYPE_JSON_FILE)
            {
                if (is_file($paramsString))
                {
                    $json         = file_get_contents($paramsString);
                    $this->params = json_decode($json, 1);
                }
                else
                {
                    throw new \Exception('file not exists:' . $paramsString);
                }

            }
            elseif ($paramsType == ConsleCommand::TYPE_QUERY)
            {
                parse_str($paramsString, $this->params);
            }

            return $this;
        }
    }
