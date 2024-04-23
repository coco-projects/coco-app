<?php

    declare(strict_types = 1);

    namespace Coco\cocoApp\KernelModule\Listeners;

    use Coco\cocoApp\Kernel\Abstracts\CoreEventAbstract;
    use Coco\cocoApp\Kernel\Abstracts\EventListenerAbstract;
    use Coco\cocoApp\Kernel\CocoApp;

    class ThrowExceptionMsgListener extends EventListenerAbstract
    {
        public function __construct()
        {
            parent::__construct(CocoApp::getInstance());
        }

        public function exec(CoreEventAbstract $coreEventAbstract): void
        {
            $msg = implode('', [
                "[Exception]:" . $this->cocoApp->process->getResultMessage(),
                PHP_EOL,
                "[file]:" . __FILE__,
            ]);

            throw new \RuntimeException($msg);
        }
    }
