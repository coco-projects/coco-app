<?php

    namespace Coco\cocoApp\Kernel\Services;

    use Coco\cocoApp\Kernel\AppInfoRegistry;
    use DI\Container;
    use Coco\cocoApp\Kernel\Abstracts\ServiceProviderAbstract;

    class AppInfoRegistryProvider extends ServiceProviderAbstract
    {
        public static string $name = 'appInfoRegistry';

        public function register(Container $container): void
        {
            $container->set(static::$name, function(Container $container) {
                return new AppInfoRegistry();
            });
        }
    }