<?php

    namespace Coco\cocoApp\Kernel\Server;

    use Coco\cocoApp\Kernel\Abstracts\ServerAbstract;
    use Coco\cocoApp\KernelModule\Events\ConsoleInitEndEvent;
    use Coco\cocoApp\KernelModule\Events\ConsoleInitStartEvent;
    use Coco\cocoApp\KernelModule\Listeners\ServerRunner\ConsoleRunListener;

    class ConsoleServer extends ServerAbstract
    {
        protected function getRunnerListener(): ConsoleRunListener
        {
            return new ConsoleRunListener();
        }

        protected function initServer(): void
        {
            $this->cocoApp->event->triggerEvent(new ConsoleInitStartEvent($this));

            $booters = $this->cocoApp->getBooters();

            foreach ($booters as $appName => $booter)
            {
                //加载命令定义
                $this->cocoApp->registerCommandsBydir($booter->getAppInfo()->getCommandsDir());
            }

            $this->cocoApp->event->triggerEvent(new ConsoleInitEndEvent($this));
        }
    }
