<?php

    namespace Coco\cocoApp\Kernel\Services;

    use Coco\config\Config;
    use DI\Container;
    use Coco\cocoApp\Kernel\Abstracts\ServiceProviderAbstract;

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