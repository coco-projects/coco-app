<?php

    namespace Coco\cocoApp\KernelModule\Services\Logger\Hander;

    use Coco\cocoApp\Kernel\Abstracts\ServiceProviderAbstract;
    use Coco\cocoApp\Kernel\CocoApp;
    use DI\Container;
    use Monolog\Handler\ErrorLogHandler;

    class ErrorLogHandlerProvider extends ServiceProviderAbstract
    {
        public static string $name = ErrorLogHandler::class;

        public function register(Container $container): void
        {
            $container->set(static::$name, function(Container $container) {


                /**
                 * @var CocoApp $app
                 */
                $app = $container->get('cocoApp');

                return new ErrorLogHandler(

                    $app->config->log->ErrorLogHandler->messageType,
                    $app->config->log->ErrorLogHandler->level,
                    $app->config->log->ErrorLogHandler->bubble,
                    $app->config->log->ErrorLogHandler->expandNewlines);
            });
        }
    }