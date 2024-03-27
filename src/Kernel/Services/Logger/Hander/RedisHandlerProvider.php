<?php

    namespace Coco\cocoApp\Kernel\Services\Logger\Hander;

    use \Coco\cocoApp\CocoApp;
    use Coco\cocoApp\Kernel\Abstracts\ServiceProviderAbstract;
    use DI\Container;
    use Monolog\Handler\RedisHandler;

    class RedisHandlerProvider extends ServiceProviderAbstract
    {
        public static string $name = RedisHandler::class;

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
//                $redisClient = $container->get('predis');

                $redisClient->select($app->config->log->RedisHandler->db_index);

                return new RedisHandler($redisClient,
                    $app->config->log->RedisHandler->key,
                    $app->config->log->RedisHandler->level,
                    $app->config->log->RedisHandler->bubble,
                    $app->config->log->RedisHandler->capSize);
            });
        }
    }