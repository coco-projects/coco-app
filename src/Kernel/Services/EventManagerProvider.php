<?php

    namespace Coco\cocoApp\Kernel\Services;

    use DI\Container;
    use Coco\cocoApp\Kernel\Abstracts\ServiceProviderAbstract;
    use Laminas\EventManager\EventManager;

    class EventManagerProvider extends ServiceProviderAbstract
    {
        public static string $name = 'event';

        public function register(Container $container): void
        {
            $container->set(static::$name, function(Container $container) {
                return new EventManager();
            });
        }
    }