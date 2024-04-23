<?php

    namespace Coco\cocoApp\Kernel\Business\ControllerWrapper;

    use Coco\cocoApp\Kernel\CocoApp;
    use Coco\cocoApp\Kernel\Traits\AppBaseTrait;

    abstract class ControllerWrapperAbstract
    {
        use AppBaseTrait;

        protected static function getIns(): static
        {
            return new static(CocoApp::getInstance());
        }
    }