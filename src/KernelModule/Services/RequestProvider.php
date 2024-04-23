<?php

    namespace Coco\cocoApp\KernelModule\Services;

    use Coco\cocoApp\Kernel\Abstracts\ServiceProviderAbstract;
    use DI\Container;
    use Slim\Factory\ServerRequestCreatorFactory;

    class RequestProvider extends ServiceProviderAbstract
    {
        public static string $name = 'request';

        public function register(Container $container): void
        {
            $container->set(static::$name, function(Container $container) {
                $serverRequestCreator = ServerRequestCreatorFactory::create();

                return $serverRequestCreator->createServerRequestFromGlobals();
            });
        }
    }