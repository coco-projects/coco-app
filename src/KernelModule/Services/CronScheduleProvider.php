<?php

    namespace Coco\cocoApp\KernelModule\Services;

    use Coco\cocoApp\Kernel\Abstracts\ServiceProviderAbstract;
    use Coco\cron\Schedule;
    use DI\Container;

    class CronScheduleProvider extends ServiceProviderAbstract
    {
        public static string $name = 'cron';

        public function register(Container $container): void
        {
            $container->set(static::$name, function(Container $container) {
                return new Schedule();
            });
        }
    }