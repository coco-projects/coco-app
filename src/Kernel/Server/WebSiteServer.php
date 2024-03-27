<?php

    namespace Coco\cocoApp\Kernel\Server;

    use Coco\cocoApp\CocoAppConsts;
    use Coco\cocoApp\Kernel\Exceptions\KernelException;
    use Coco\constants\Consts;
    use Coco\cocoApp\Kernel\Abstracts\BooterAbstract;
    use Coco\cocoApp\Kernel\Abstracts\ServerAbstract;
    use Coco\cocoApp\Kernel\Listeners\WebRunListener;
    use Coco\cocoApp\Kernel\Services\ConfigProvider;
    use Coco\cocoApp\Kernel\Utils;

    class WebSiteServer extends ServerAbstract
    {
        public function boot(): void
        {
            $app = $this->cocoApp;

            $this->initBooters($app->getBooters());

            $app->afterInitBooter();
        }

        /**
         * @param BooterAbstract[] $booters
         *
         * @return $this
         * @throws KernelException
         */
        public function initBooters(array $booters): static
        {
            $app = $this->cocoApp;

            if (count($booters))
            {
                foreach ($booters as $appName => $booter)
                {
                    $app->registerConfigsBydir($booter->getAppInfo()->getConfigDir());

                    foreach ($app->getUserConfigs() as $configDir)
                    {
                        $app->registerConfigsBydir($configDir);
                    }
                }

                $app->registerService(ConfigProvider::class);

                foreach ($booters as $appName => $booter)
                {
                    $this->registerMiddlewareBydir($booter->getAppInfo()->getMiddlewareDir());
                    $this->registerRoutersBydir($booter->getAppInfo()->getRoutesDir());
                    $app->registerServicesByDir($booter->getAppInfo()->getServicesDir());
                    $app->registerEventListenersByDir($booter->getAppInfo()->getListenersDir());
                    $app->appInfoRegistry->addApp($booter->getAppInfo());
                }
            }

            $this->registerMiddleware(require Consts::getValue('CORE_RESOURCE_PATH') . 'defaultMiddleware.php');

            return $this;
        }

        protected function initRunnerListener(): static
        {
            $this->cocoApp->registerEventListener(CocoAppConsts::CORE_PROCESS_RUN, new WebRunListener($this->cocoApp));

            return $this;
        }

        public function registerRoutersBydir($path): static
        {
            Utils::scanDir($path, function($file) {
                $callback = require $file;

                if (is_callable($callback))
                {
                    $this->registerRouter($callback);
                }
            });

            return $this;
        }

        public function registerRouter(callable $callback): static
        {
            call_user_func_array($callback, [$this->cocoApp->slim]);

            return $this;
        }


        public function registerMiddlewareBydir($path): static
        {
            Utils::scanDir($path, function($file) {
                $callback = require $file;

                if (is_callable($callback))
                {
                    $this->registerMiddleware($callback);
                }
            });

            return $this;
        }

        public function registerMiddleware(callable $callback): static
        {
            call_user_func_array($callback, [$this->cocoApp->slim]);

            return $this;
        }


    }
