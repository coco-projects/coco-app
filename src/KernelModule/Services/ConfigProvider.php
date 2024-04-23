<?php

    namespace Coco\cocoApp\KernelModule\Services;

    use Coco\cocoApp\Kernel\Abstracts\ServiceProviderAbstract;
    use Coco\config\Config;
    use DI\Container;

    class ConfigProvider extends ServiceProviderAbstract
    {
        public static string $name = 'config';

        public function register(Container $container): void
        {
            $container->set(static::$name, function(Container $container) {
                return new Config($container->get('cocoApp')->getAllAppConfigs());
            });
        }
    }