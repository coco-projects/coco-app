<?php

    namespace Coco\cocoApp\Kernel\Services;

    use Coco\cocoApp\Kernel\Business\ConsleCommand;
    use DI\Container;
    use Coco\cocoApp\Kernel\Abstracts\ServiceProviderAbstract;

    class ConsleCommandProvider extends ServiceProviderAbstract
    {
        public static string $name = 'consleCommand';

        public function register(Container $container): void
        {
            $container->set(static::$name, function(Container $container) {
                return new ConsleCommand();
            });
        }
    }