<?php

    namespace Coco\cocoApp\Kernel\Services;

    use Coco\timer\Timer;
    use DI\Container;
    use Coco\cocoApp\Kernel\Abstracts\ServiceProviderAbstract;

    class TimerProvider extends ServiceProviderAbstract
    {
        public static string $name = 'timer';

        public function register(Container $container): void
        {
            $container->set(static::$name, function(Container $container) {
                return new Timer();
            });
        }
    }