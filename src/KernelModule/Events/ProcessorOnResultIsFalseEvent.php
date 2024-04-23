<?php

    declare(strict_types = 1);

    namespace Coco\cocoApp\KernelModule\Events;

    use Coco\cocoApp\Kernel\Abstracts\CoreEventAbstract;
    use Coco\cocoApp\Kernel\CocoAppConsts;

    class ProcessorOnResultIsFalseEvent extends CoreEventAbstract
    {
        protected static string $eventName = CocoAppConsts::CORE_PROCESS_ON_RESULT_IS_FALSE;

        public static function getEventName(): string
        {
            return static::$eventName;
        }
    }
