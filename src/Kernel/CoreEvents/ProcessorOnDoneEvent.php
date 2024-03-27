<?php

    declare(strict_types = 1);

    namespace Coco\cocoApp\Kernel\CoreEvents;

    use Coco\cocoApp\CocoAppConsts;
    use Coco\cocoApp\Kernel\Abstracts\CoreEventAbstract;

    class ProcessorOnDoneEvent extends CoreEventAbstract
    {
        protected static string $eventName = CocoAppConsts::CORE_PROCESS_ON_DONE;

        public static function getEventName(): string
        {
            return static::$eventName;
        }
    }
