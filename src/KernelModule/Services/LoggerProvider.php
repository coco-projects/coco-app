<?php

    namespace Coco\cocoApp\KernelModule\Services;

    use Coco\cocoApp\Kernel\Abstracts\ServiceProviderAbstract;
    use DI\Container;
    use Monolog\Logger;

    class LoggerProvider extends ServiceProviderAbstract
    {
        public static string $name = 'logger';

        public function register(Container $container): void
        {
            $container->set(static::$name, function(Container $container) {
                return new Logger('coco');
            });
        }
    }