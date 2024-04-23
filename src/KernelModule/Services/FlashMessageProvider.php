<?php

    namespace Coco\cocoApp\KernelModule\Services;

    use Coco\cocoApp\Kernel\Abstracts\ServiceProviderAbstract;
    use DI\Container;
    use Slim\Flash\Messages;

    class FlashMessageProvider extends ServiceProviderAbstract
    {
        public static string $name = 'flash';

        public function register(Container $container): void
        {
            $container->set(static::$name, function(Container $container) {
                return new Messages();
            });
        }
    }