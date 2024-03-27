<?php

    namespace Coco\cocoApp\Kernel\Services;

    use DI\Container;
    use Coco\cocoApp\Kernel\Abstracts\ServiceProviderAbstract;
    use Slim\Factory\AppFactory;

    class SlimAppProvider extends ServiceProviderAbstract
    {
        public static string $name = 'slim';

        public function register(Container $container): void
        {
            $container->set(static::$name, function(Container $container) {
                return AppFactory::createFromContainer($container);
            });
        }
    }