<?php

    namespace Coco\cocoApp\Kernel\Abstracts;

    use Coco\cocoApp\Kernel\Traits\AppBaseTrait;

    abstract class EventListenerAbstract
    {
        use AppBaseTrait;

        protected int $priority = 10000;

        public function getCallable(): callable
        {
            return [
                $this,
                'exec',
            ];
        }

        /**
         * @return int
         */
        public function getPriority(): int
        {
            return $this->priority;
        }

        abstract public function exec(CoreEventAbstract $coreEventAbstract): void;
    }