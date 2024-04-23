<?php

    declare(strict_types = 1);

    namespace Coco\cocoApp\KernelModule\Events;

    use Coco\cocoApp\Kernel\Abstracts\CoreEventAbstract;
    use Coco\cocoApp\Kernel\CocoAppConsts;

    class ConsoleInitStartEvent extends CoreEventAbstract
    {
        protected static string $eventName = CocoAppConsts::CORE_CONSOLE_INIT_START;

        public static function getEventName(): string
        {
            return static::$eventName;
        }
    }
