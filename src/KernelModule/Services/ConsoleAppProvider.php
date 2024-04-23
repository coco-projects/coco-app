<?php

    namespace Coco\cocoApp\KernelModule\Services;

    use Coco\cocoApp\Kernel\Abstracts\ServiceProviderAbstract;
    use DI\Container;
    use Symfony\Component\Console\Application;

    class ConsoleAppProvider extends ServiceProviderAbstract
    {
        public static string $name = 'console';

        public function register(Container $container): void
        {
            $container->set(static::$name, function(Container $container) {
                return new Application();
            });
        }
    }