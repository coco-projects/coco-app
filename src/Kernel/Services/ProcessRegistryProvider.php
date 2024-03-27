<?php

    namespace Coco\cocoApp\Kernel\Services;

    use Coco\processManager\ProcessRegistry;
    use DI\Container;
    use Coco\cocoApp\Kernel\Abstracts\ServiceProviderAbstract;

    class ProcessRegistryProvider extends ServiceProviderAbstract
    {
        public static string $name = 'process';

        public function register(Container $container): void
        {
            $container->set(static::$name, function(Container $container) {
                return new ProcessRegistry();
            });
        }
    }