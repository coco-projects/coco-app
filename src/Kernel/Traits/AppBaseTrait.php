<?php

    namespace Coco\cocoApp\Kernel\Traits;

    use Coco\cocoApp\Kernel\CocoApp;

    trait AppBaseTrait
    {
        public ?CocoApp $cocoApp = null;

        public function __construct(CocoApp $cocoApp)
        {
            $this->cocoApp = $cocoApp;
        }
    }