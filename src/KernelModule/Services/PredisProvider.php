<?php

    namespace Coco\cocoApp\KernelModule\Services;

    use Coco\cocoApp\Kernel\Abstracts\ServiceProviderAbstract;
    use Coco\cocoApp\Kernel\CocoApp;
    use DI\Container;
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