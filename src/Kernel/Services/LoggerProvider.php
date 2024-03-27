<?php

    namespace Coco\cocoApp\Kernel\Services;

    use DI\Container;
    use Coco\cocoApp\Kernel\Abstracts\ServiceProviderAbstract;
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