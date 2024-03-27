<?php

    declare(strict_types = 1);

    namespace Coco\cocoApp\Kernel\CoreEvents;

    use Coco\cocoApp\CocoAppConsts;
    use Coco\cocoApp\Kernel\Abstracts\CoreEventAbstract;

    class SystemInitStartEvent extends CoreEventAbstract
    {
        protected static string $eventName = CocoAppConsts::CORE_SYSTEM_INIT_START;

        public static function getEventName(): string
        {
            return static::$eventName;
        }
    }
