<?php

    namespace Coco\cocoApp\Kernel\Server;

    use Coco\cocoApp\CocoAppConsts;
    use Coco\cocoApp\Kernel\Abstracts\BooterAbstract;
    use Coco\cocoApp\Kernel\Abstracts\ServerAbstract;
    use Coco\cocoApp\Kernel\Exceptions\KernelException;
    use Coco\cocoApp\Kernel\Listeners\ConsoleRunListener;
    use Coco\cocoApp\Kernel\Services\ConfigProvider;
    use Coco\cocoApp\Kernel\Utils;

    class ConsoleServer extends ServerAbstract
    {
        protected function boot(): void
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
        protected function initBooters(array $booters): static
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
                    $this->registerCommandsBydir($booter->getAppInfo()->getCommandsDir());
                    $app->registerServicesByDir($booter->getAppInfo()->getServicesDir());
                    $app->registerEventListenersByDir($booter->getAppInfo()->getListenersDir());
                    $app->appInfoRegistry->addApp($booter->getAppInfo());
                }
            }

            return $this;
        }

        protected function initRunnerListener(): static
        {
            $this->cocoApp->registerEventListener(CocoAppConsts::CORE_PROCESS_RUN, new ConsoleRunListener($this->cocoApp));

            return $this;
        }

        public function registerCommandsBydir($path): static
        {
            Utils::scanDir($path, function($file) {
                $callback = require $file;

                if (is_callable($callback))
                {
                    $this->registerCommand($callback);
                }
            });

            return $this;
        }

        public function registerCommand(callable $callback): static
        {
            call_user_func_array($callback, [$this->cocoApp->consleCommand]);

            return $this;
        }

    }
