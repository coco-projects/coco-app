<?php

    namespace Coco\cocoApp\Kernel\Business\ControllerAbstract;

    use Coco\cocoApp\Kernel\Abstracts\AppInfoAbstract;
    use Coco\cocoApp\Kernel\CocoApp;
    use Coco\macroable\Macroable;

    abstract class ControllerAbstract
    {
        use Macroable;

        public ?CocoApp $cocoApp;

        abstract static public function getAppName(): string;

        public function getInfo(): AppInfoAbstract
        {
            return $this->cocoApp->getBooterByAppName(static::getAppName())->getAppInfo();
        }
    }