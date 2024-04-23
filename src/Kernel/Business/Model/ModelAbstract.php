<?php

    namespace Coco\cocoApp\Kernel\Business\Model;

    use Coco\cocoApp\Kernel\CocoApp;
    use Coco\cocoApp\Kernel\Traits\AppBaseTrait;

    abstract class ModelAbstract
    {
        use AppBaseTrait;

        /**
         * @var ModelAbstract[] $ins
         */
        private static array $ins = [];
        public mixed         $source;

        public static function getIns(): ?static
        {
            if (!isset(static::$ins[static::class]))
            {
                static::$ins[static::class] = new static(CocoApp::getInstance());
                static::$ins[static::class]->init();
            }

            return static::$ins[static::class];
        }

        protected function init()
        {

        }
    }