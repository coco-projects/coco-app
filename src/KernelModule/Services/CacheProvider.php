<?php

    namespace Coco\cocoApp\KernelModule\Services;

    use Coco\cocoApp\Kernel\Abstracts\ServiceProviderAbstract;
    use Coco\cocoApp\Kernel\CocoApp;
    use DI\Container;
    use Predis\Client;
    use Symfony\Component\Cache\Adapter\RedisTagAwareAdapter;

    class CacheProvider extends ServiceProviderAbstract
    {
        public static string $name = 'cache';

        public function register(Container $container): void
        {
            $container->set(static::$name, function(Container $container) {

                /**
                 * @var CocoApp $app
                 */
                $app = $container->get('cocoApp');

                /**
                 * @var \Redis $redisClient
                 */
                $redisClient = $container->get('redis');

                /**
                 * @var Client $predisClient
                 */
                $predisClient = $container->get('predis');

                $redisClient->select($app->config->symfony_cache->db_index);

                return new RedisTagAwareAdapter($redisClient, $app->config->symfony_cache->prefix);
            });
        }
    }