<?php

    declare(strict_types = 1);

    namespace Coco\cocoApp\Kernel\CoreEvents;

    use Coco\cocoApp\CocoAppConsts;
    use Coco\cocoApp\Kernel\Abstracts\CoreEventAbstract;

    class ProcessorRunAfterEvent extends CoreEventAbstract
    {
        protected static string $eventName = CocoAppConsts::CORE_PROCESS_RUN_AFTER;

        public static function getEventName(): string
        {
            return static::$eventName;
        }
    }
