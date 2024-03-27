<?php

    namespace Coco\cocoApp\Kernel\Services\Logger\Hander;

    use \Coco\cocoApp\CocoApp;
    use Coco\cocoApp\Kernel\Abstracts\ServiceProviderAbstract;
    use DI\Container;
    use Monolog\Handler\SocketHandler;

    class SocketHandlerProvider extends ServiceProviderAbstract
    {
        public static string $name = SocketHandler::class;

        public function register(Container $container): void
        {
            $container->set(static::$name, function(Container $container) {

                /**
                 * @var CocoApp $app
                 */
                $app = $container->get('cocoApp');

                return new SocketHandler(

                    $app->config->log->SocketHandler->connectionString,
                    $app->config->log->SocketHandler->level,
                    $app->config->log->SocketHandler->bubble,
                    $app->config->log->SocketHandler->persistent,
                    $app->config->log->SocketHandler->timeout,
                    $app->config->log->SocketHandler->writingTimeout,
                    $app->config->log->SocketHandler->connectionTimeout,
                    $app->config->log->SocketHandler->chunkSize);
            });
        }
    }