<?php

    namespace Coco\cocoApp\KernelModule\Services;

    use Coco\cocoApp\Kernel\Abstracts\ServiceProviderAbstract;
    use Coco\constants\Consts;
    use DI\Container;
    use Slim\Views\Twig;

    class ViewProvider extends ServiceProviderAbstract
    {
        public static string $name = 'view';

        public function register(Container $container): void
        {
            $container->set(static::$name, function(Container $container) {
                $view = Twig::create(Consts::getValue('CORE_RESOURCE_PATH') . DIRECTORY_SEPARATOR . 'template', [
                    'debug'            => !!$container->get('config')->base->app_debug,
                    'charset'          => 'UTF-8',
                    'strict_variables' => false,
                    'autoescape'       => 'html',
                    'cache'            => false,
                    'auto_reload'      => null,
                    'optimizations'    => -1,
                ]);

                return $view;
            });
        }
    }