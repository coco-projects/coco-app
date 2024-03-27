<?php

    declare(strict_types = 1);

    namespace Coco\cocoApp\Kernel\Listeners;

    use \Coco\cocoApp\CocoApp;
    use Coco\cocoApp\Kernel\Abstracts\CoreEventAbstract;
    use Coco\cocoApp\Kernel\Abstracts\EventListenerAbstract;

    class EchoLoggerListener extends EventListenerAbstract
    {
        public function __construct(CocoApp $app)
        {
            parent::__construct($app);
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
