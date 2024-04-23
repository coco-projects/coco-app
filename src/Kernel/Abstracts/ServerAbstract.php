<?php

    namespace Coco\cocoApp\Kernel\Abstracts;

    use Coco\cocoApp\Kernel\CocoApp;
    use Coco\cocoApp\Kernel\CocoAppConsts;
    use Coco\cocoApp\KernelModule\Events\SystemInitEndEvent;
    use Coco\cocoApp\KernelModule\Events\SystemInitStartEvent;
    use Coco\cocoApp\KernelModule\Services\ConfigProvider;

    abstract class ServerAbstract
    {
        public CocoApp $cocoApp;

        public function __construct(CocoApp $cocoApp)
        {
            $this->cocoApp = $cocoApp;

            $this->cocoApp->setServer($this);

            $this->initCommon();

            $this->cocoApp->event->triggerEvent(new SystemInitStartEvent($this));

            $this->initServer();

            $this->cocoApp->event->triggerEvent(new SystemInitEndEvent($this));
        }

        protected function initCommon(): static
        {
            $booters = $this->cocoApp->getBooters();

            foreach ($booters as $AppName => $booter)
            {
                //加载模块预定义配置
                $this->cocoApp->registerConfigsBydir($booter->getAppInfo()->getConfigDir());

                //加载用户自定义配置
                if (is_string($booter->getUserConfigDir()) && is_dir($booter->getUserConfigDir()))
                {
                    $this->cocoApp->registerConfigsBydir($booter->getUserConfigDir());
                }
            }

            //注册配置实例
            $this->cocoApp->registerService(ConfigProvider::class);

            foreach ($booters as $AppName => $booter)
            {
                //注册服务
                $this->cocoApp->registerServicesByDir($booter->getAppInfo()->getServicesDir());

                //注册事件监听
                $this->cocoApp->registerEventListenersByDir($booter->getAppInfo()->getListenersDir());

                //注册定时任务
                $this->cocoApp->registerCronBydir($booter->getAppInfo()->getCronsDir());
            }

            //注册响应监听器
            $this->cocoApp->registerEventListener(CocoAppConsts::CORE_PROCESS_RUN, $this->getRunnerListener());

            return $this;
        }

        public function listen(): void
        {
            $this->cocoApp->listen();
        }


        abstract protected function getRunnerListener(): EventListenerAbstract;

        abstract protected function initServer(): void;
    }