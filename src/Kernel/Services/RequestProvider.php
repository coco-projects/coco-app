<?php

    namespace Coco\cocoApp\Kernel\Services;

    use DI\Container;
    use Coco\cocoApp\Kernel\Abstracts\ServiceProviderAbstract;
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