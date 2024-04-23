<?php

    declare(strict_types = 1);

    namespace Coco\cocoApp\KernelModule\Listeners;

    use Coco\cocoApp\Kernel\Abstracts\CoreEventAbstract;
    use Coco\cocoApp\Kernel\Abstracts\EventListenerAbstract;
    use Coco\cocoApp\Kernel\CocoApp;

    class SystemInitListener extends EventListenerAbstract
    {
        public function __construct()
        {
            parent::__construct(CocoApp::getInstance());
        }

        public function exec(CoreEventAbstract $coreEventAbstract): void
        {
            date_default_timezone_set($this->cocoApp->config->base->timezone);
            error_reporting($this->cocoApp->config->base->error_reporting);
            ini_set('display_errors', $this->cocoApp->config->base->display_errors);

//            set_error_handler([$this, 'appError']);
//            set_exception_handler([$this, 'appException']);
//            register_shutdown_function([$this, 'appShutdown']);

        }
    }
