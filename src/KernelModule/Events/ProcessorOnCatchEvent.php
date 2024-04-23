<?php

    declare(strict_types = 1);

    namespace Coco\cocoApp\KernelModule\Events;

    use Coco\cocoApp\Kernel\Abstracts\CoreEventAbstract;
    use Coco\cocoApp\Kernel\CocoAppConsts;

    class ProcessorOnCatchEvent extends CoreEventAbstract
    {
        protected static string $eventName = CocoAppConsts::CORE_PROCESS_ON_CATCH;

        public static function getEventName(): string
        {
            return static::$eventName;
        }
    }
