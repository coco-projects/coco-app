<?php

    declare(strict_types = 1);

    namespace Coco\cocoApp\KernelModule\Listeners;

    use Coco\cocoApp\Kernel\Abstracts\CoreEventAbstract;
    use Coco\cocoApp\Kernel\Abstracts\EventListenerAbstract;
    use Coco\cocoApp\Kernel\CocoApp;

    class EchoLoggerListener extends EventListenerAbstract
    {
        public function __construct()
        {
            parent::__construct(CocoApp::getInstance());
        }

        public function exec(CoreEventAbstract $coreEventAbstract): void
        {
            $msg = implode('', [
                '【',
                "[event]:" . $coreEventAbstract->getName(),
                '】',
                PHP_EOL,
            ]);

            echo $msg;
        }
    }
