<?php

    namespace Coco\cocoApp\Kernel\Server;

    use Coco\cocoApp\Kernel\Abstracts\ServerAbstract;
    use Coco\cocoApp\KernelModule\Events\WebSiteServerInitEndEvent;
    use Coco\cocoApp\KernelModule\Events\WebSiteServerInitStartEvent;
    use Coco\cocoApp\KernelModule\Listeners\ServerRunner\WebRunListener;

    class WebSiteServer extends ServerAbstract
    {
        protected function getRunnerListener(): WebRunListener
        {
            return new WebRunListener();
        }

        protected function initServer(): void
        {
            $this->cocoApp->event->triggerEvent(new WebSiteServerInitStartEvent($this));

            $booters = $this->cocoApp->getBooters();

            foreach ($booters as $appName => $booter)
            {
                //加载路由
                $this->cocoApp->registerRoutersBydir($booter->getAppInfo()->getRoutesDir());
            }

            foreach ($booters as $appName => $booter)
            {
                //注册中间件
                $this->cocoApp->registerMiddlewareBydir($booter->getAppInfo()->getMiddlewareDir());
            }

            $this->cocoApp->event->triggerEvent(new WebSiteServerInitEndEvent($this));
        }

    }
