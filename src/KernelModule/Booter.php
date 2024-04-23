<?php

    namespace Coco\cocoApp\KernelModule;

    use Coco\cocoApp\Kernel\Abstracts\BooterAbstract;
    use Coco\cocoApp\Kernel\CocoApp;

    class Booter extends BooterAbstract
    {
        public function __construct(CocoApp $cocoApp)
        {
            parent::__construct($cocoApp);
            $this->appInfo = new Info($this->cocoApp);
        }
    }