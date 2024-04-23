<?php

    namespace Coco\cocoApp\KernelModule\Services\Logger\Hander;

    use Coco\cocoApp\Kernel\Abstracts\ServiceProviderAbstract;
    use Coco\cocoApp\Kernel\CocoApp;
    use DI\Container;
    use Monolog\Handler\RotatingFileHandler;

    class RotatingFileHandlerProvider extends ServiceProviderAbstract
    {
        public static string $name = RotatingFileHandler::class;

        public function register(Container $container): void
        {
            $container->set(static::$name, function(Container $container) {

                /**
                 * @var CocoApp $app
                 */
                $app = $container->get('cocoApp');

                return new RotatingFileHandler(

                    $app->config->log->RotatingFileHandler->filename,
                    $app->config->log->RotatingFileHandler->maxFiles,
                    $app->config->log->RotatingFileHandler->level,
                    $app->config->log->RotatingFileHandler->bubble,
                    $app->config->log->RotatingFileHandler->filePermission,
                    $app->config->log->RotatingFileHandler->useLocking,
                    $app->config->log->RotatingFileHandler->dateFormat,
                    $app->config->log->RotatingFileHandler->filenameFormat);
            });
        }
    }