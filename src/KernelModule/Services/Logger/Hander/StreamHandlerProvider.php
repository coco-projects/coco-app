<?php

    namespace Coco\cocoApp\KernelModule\Services\Logger\Hander;

    use Coco\cocoApp\Kernel\Abstracts\ServiceProviderAbstract;
    use Coco\cocoApp\Kernel\CocoApp;
    use DI\Container;
    use Monolog\Handler\StreamHandler;

    class StreamHandlerProvider extends ServiceProviderAbstract
    {
        public static string $name = StreamHandler::class;

        public function register(Container $container): void
        {
            $container->set(static::$name, function(Container $container) {

                /**
                 * @var CocoApp $app
                 */
                $app = $container->get('cocoApp');

                return new StreamHandler(

                    $app->config->log->StreamHandler->stream,
                    $app->config->log->StreamHandler->level,
                    $app->config->log->StreamHandler->bubble,
                    $app->config->log->StreamHandler->filePermission,
                    $app->config->log->StreamHandler->useLocking);
            });
        }
    }