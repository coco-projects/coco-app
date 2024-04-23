<?php

    namespace Coco\cocoApp\KernelModule\Services;

    use Coco\cocoApp\Kernel\Abstracts\ServiceProviderAbstract;
    use Coco\cocoApp\Kernel\Business\ConsleCommand;
    use DI\Container;

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