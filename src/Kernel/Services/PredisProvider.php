<?php

    namespace Coco\cocoApp\Kernel\Services;

    use \Coco\cocoApp\CocoApp;
    use DI\Container;
    use Coco\cocoApp\Kernel\Abstracts\ServiceProviderAbstract;
    use Predis\Client;

    class PredisProvider extends ServiceProviderAbstract
    {
        public static string $name = 'predis';

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

                $config = [
                    'scheme' => 'tcp',
                    'host'   => $redisHost,
                    'port'   => $redisPort,
                ];

                if ($redisPassword)
                {
                    $config['password'] = $redisPassword;
                }

                return new Client($config);
            });
        }
    }