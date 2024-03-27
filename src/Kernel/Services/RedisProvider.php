<?php

    namespace Coco\cocoApp\Kernel\Services;

    use \Coco\cocoApp\CocoApp;
    use DI\Container;
    use Coco\cocoApp\Kernel\Abstracts\ServiceProviderAbstract;

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