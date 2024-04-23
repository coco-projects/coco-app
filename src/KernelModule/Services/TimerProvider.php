<?php

    namespace Coco\cocoApp\KernelModule\Services;

    use Coco\cocoApp\Kernel\Abstracts\ServiceProviderAbstract;
    use Coco\timer\Timer;
    use DI\Container;

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