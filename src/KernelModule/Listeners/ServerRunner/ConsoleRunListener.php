<?php

    declare(strict_types = 1);

    namespace Coco\cocoApp\KernelModule\Listeners\ServerRunner;

    use Coco\cocoApp\Kernel\Abstracts\CoreEventAbstract;
    use Coco\cocoApp\Kernel\Abstracts\EventListenerAbstract;
    use Coco\cocoApp\Kernel\CocoApp;

    class ConsoleRunListener extends EventListenerAbstract
    {
        public function __construct()
        {
            parent::__construct(CocoApp::getInstance());
        }

        public function exec(CoreEventAbstract $coreEventAbstract): void
        {
            $this->cocoApp->console->add($this->cocoApp->consleCommand);
            $this->cocoApp->console->run();
        }
    }
