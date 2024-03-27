<?php

    namespace Coco\cocoApp\Kernel\Services;

    use \Coco\cocoApp\CocoApp;
    use DI\Container;
    use Coco\cocoApp\Kernel\Abstracts\ServiceProviderAbstract;

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