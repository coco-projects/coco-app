<?php

    namespace Coco\cocoApp\KernelModule\Services;

    use Coco\cocoApp\Kernel\Abstracts\ServiceProviderAbstract;
    use Coco\cocoApp\Kernel\CocoApp;
    use DI\Container;

    class CocoAppProvider extends ServiceProviderAbstract
    {
        public static string $name = 'cocoApp';

        public function register(Container $container): void
        {
            $container->set(static::$name, function(Container $container) {
                return CocoApp::getInstance();
            });
        }
    }