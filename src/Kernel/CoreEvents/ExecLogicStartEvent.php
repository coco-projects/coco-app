<?php

    declare(strict_types = 1);

    namespace Coco\cocoApp\Kernel\CoreEvents;

    use Coco\cocoApp\CocoAppConsts;
    use Coco\cocoApp\Kernel\Abstracts\CoreEventAbstract;

    class ExecLogicStartEvent extends CoreEventAbstract
    {
        protected static string $eventName = CocoAppConsts::CORE_RUN_LOGIC_START;

        public static function getEventName(): string
        {
            return static::$eventName;
        }
    }
