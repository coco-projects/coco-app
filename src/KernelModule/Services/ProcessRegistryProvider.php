<?php

    namespace Coco\cocoApp\KernelModule\Services;

    use Coco\cocoApp\Kernel\Abstracts\ServiceProviderAbstract;
    use Coco\processManager\ProcessRegistry;
    use DI\Container;

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