<?php

    namespace Coco\cocoApp\KernelModule\Services;

    use Coco\cocoApp\Kernel\Abstracts\ServiceProviderAbstract;
    use DI\Container;
    use Slim\Csrf\Guard;

    class CsrfProvider extends ServiceProviderAbstract
    {
        public static string $name = 'csrf';

        public function register(Container $container): void
        {
            $container->set(static::$name, function(Container $container) {


                return new Guard($container->get('router'));
            });
        }
    }