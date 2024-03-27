<?php

    declare(strict_types = 1);

    namespace Coco\cocoApp\Kernel\CoreEvents;

    use Coco\cocoApp\CocoAppConsts;
    use Coco\cocoApp\Kernel\Abstracts\CoreEventAbstract;

    class ProcessorOnResultIsTrueEvent extends CoreEventAbstract
    {
        protected static string $eventName = CocoAppConsts::CORE_PROCESS_ON_RESULT_IS_TRUE;

        public static function getEventName(): string
        {
            return static::$eventName;
        }
    }

