<?php

    namespace Coco\cocoApp\Kernel\Abstracts;

    use Laminas\EventManager\Event;

    abstract class CoreEventAbstract extends Event
    {
        protected static string $eventName;

        public function __construct($target, $param = [])
        {
            parent::__construct(static::getEventName(), $target, $param);
        }

        abstract public static function getEventName(): string;
    }