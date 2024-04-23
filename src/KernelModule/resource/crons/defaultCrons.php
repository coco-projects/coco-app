<?php

    use Coco\cocoApp\Kernel\CocoApp;
    use Coco\cron\abstract\JobAbstract;
    use Coco\cron\Schedule;
    use Coco\cron\job\CallableJob;

    return function(Schedule $schedule) {
        /**
         * @var CocoApp $cocoApp
         */
        $cocoApp = CocoApp::getInstance();
        $appName = \Coco\cocoApp\KernelModule\Info::getAppName();

    };