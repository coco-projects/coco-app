<?php

    namespace Coco\cocoApp\Kernel\Business\ControllerAbstract;

    class ConsoleClosureController extends ConsoleControllerAbstract
    {
        protected static string $appName;

        public function __construct($appName, $ins)
        {
            static::$appName = $appName;
            parent::__construct($ins);
        }

        public static function getAppName(): string
        {
            return static::$appName;
        }
    }