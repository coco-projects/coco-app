<?php

    namespace Coco\cocoApp\KernelModule\Services;

    use Coco\cocoApp\Kernel\Abstracts\ServiceProviderAbstract;
    use DI\Container;
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