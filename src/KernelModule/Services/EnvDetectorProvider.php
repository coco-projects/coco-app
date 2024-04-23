<?php

    namespace Coco\cocoApp\KernelModule\Services;

    use Coco\cocoApp\Kernel\Abstracts\ServiceProviderAbstract;
    use Coco\envDetector\Factory;
    use DI\Container;

    class EnvDetectorProvider extends ServiceProviderAbstract
    {
        public static string $name = 'envDetector';

        public function register(Container $container): void
        {
            $container->set(static::$name, function(Container $container) {
                return Factory::getIns(new \Coco\envDetector\ip2Region\Channel1());
            });
        }
    }