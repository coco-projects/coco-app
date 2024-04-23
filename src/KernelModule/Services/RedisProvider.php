<?php

    namespace Coco\cocoApp\KernelModule\Services;

    use Coco\cocoApp\Kernel\Abstracts\ServiceProviderAbstract;
    use Coco\cocoApp\Kernel\CocoApp;
    use DI\Container;

    class RedisProvider extends ServiceProviderAbstract
    {
        public static string $name = 'redis';

        public function register(Container $container): void
        {
            $container->set(static::$name, function(Container $container) {

                /**
                 * @var CocoApp $app
                 */
                $app = $container->get('cocoApp');

                $redisHost     = $app->config->redis->host;
                $redisPort     = $app->config->redis->port;
                $redisPassword = $app->config->redis->password;

                $redisClient = new \Redis();
                $redisClient->connect($redisHost, $redisPort);
                !!$redisPassword and $redisClient->auth($redisPassword);

                return $redisClient;
            });
        }
    }