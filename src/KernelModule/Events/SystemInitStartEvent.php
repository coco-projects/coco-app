<?php

    declare(strict_types = 1);

    namespace Coco\cocoApp\KernelModule\Events;

    use Coco\cocoApp\Kernel\Abstracts\CoreEventAbstract;
    use Coco\cocoApp\Kernel\CocoAppConsts;

    class SystemInitStartEvent extends CoreEventAbstract
    {
        protected static string $eventName = CocoAppConsts::CORE_SYSTEM_INIT_START;

        public static function getEventName(): string
        {
            return static::$eventName;
        }
    }
